<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\PriorityLevel;
use App\Enums\PriorityStatus;
use App\Enums\QueueTicketOrigin;
use App\Enums\QueueTicketStatus;
use App\Models\Appointment;
use App\Models\PriorityRecord;
use App\Models\ProfessionalProfile;
use App\Models\QueueTicket;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class QueueService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function issueFromAppointment(User $actor, Appointment $appointment): QueueTicket
    {
        return DB::transaction(function () use ($actor, $appointment) {
            if ($appointment->queueTicket()->exists()) {
                throw ValidationException::withMessages([
                    'appointment' => 'Já existe uma senha emitida para este agendamento.',
                ]);
            }

            if (! $appointment->scheduled_at->isToday()) {
                throw ValidationException::withMessages([
                    'appointment' => 'O check-in só pode ser feito no dia da consulta.',
                ]);
            }

            if (! in_array($appointment->status, [AppointmentStatus::Agendado, AppointmentStatus::Confirmado], true)) {
                throw ValidationException::withMessages([
                    'appointment' => 'Este agendamento não está num estado válido para check-in.',
                ]);
            }

            $now = now();

            $ticket = QueueTicket::query()->create([
                'ticket_number' => $this->generateTicketNumber($appointment->service, $now),
                'service_id' => $appointment->service_id,
                'professional_profile_id' => $appointment->professional_profile_id,
                'user_id' => $appointment->user_id,
                'appointment_id' => $appointment->id,
                'origin' => QueueTicketOrigin::Agendamento,
                'status' => QueueTicketStatus::Emitida,
                'priority_level' => PriorityLevel::Normal,
                'issued_at' => $now,
            ]);

            $appointment->update(['status' => AppointmentStatus::Confirmado]);

            $this->auditService->log($actor, 'queue_ticket.issued', $ticket, [], ['origin' => 'agendamento']);

            return $ticket;
        });
    }

    public function issueSpontaneous(User $utente, Service $service): QueueTicket
    {
        return DB::transaction(function () use ($utente, $service) {
            $existingActive = QueueTicket::query()
                ->where('user_id', $utente->id)
                ->where('service_id', $service->id)
                ->whereIn('status', QueueTicketStatus::activos())
                ->exists();

            if ($existingActive) {
                throw ValidationException::withMessages([
                    'service_id' => 'Já tem uma senha activa para este serviço.',
                ]);
            }

            $now = now();

            $ticket = QueueTicket::query()->create([
                'ticket_number' => $this->generateTicketNumber($service, $now),
                'service_id' => $service->id,
                'user_id' => $utente->id,
                'origin' => QueueTicketOrigin::Espontaneo,
                'status' => QueueTicketStatus::Emitida,
                'priority_level' => PriorityLevel::Normal,
                'issued_at' => $now,
            ]);

            $this->auditService->log($utente, 'queue_ticket.issued', $ticket, [], ['origin' => 'espontaneo']);

            return $ticket;
        });
    }

    public function requestPriority(User $utente, QueueTicket $ticket, string $reason): PriorityRecord
    {
        return DB::transaction(function () use ($utente, $ticket, $reason) {
            if ($ticket->priorityRecord()->exists()) {
                throw ValidationException::withMessages([
                    'reason' => 'Já solicitou prioridade para esta senha.',
                ]);
            }

            if ($ticket->status !== QueueTicketStatus::Emitida) {
                throw ValidationException::withMessages([
                    'reason' => 'Só é possível solicitar prioridade enquanto a senha está à espera.',
                ]);
            }

            $record = PriorityRecord::query()->create([
                'queue_ticket_id' => $ticket->id,
                'requested_level' => PriorityLevel::Prioritario,
                'reason' => $reason,
                'status' => PriorityStatus::Pendente,
            ]);

            $this->auditService->log($utente, 'priority.requested', $record, [], ['reason' => $reason]);

            return $record;
        });
    }

    public function confirmPriority(User $professionalUser, PriorityRecord $record, bool $approved): PriorityRecord
    {
        return DB::transaction(function () use ($professionalUser, $record, $approved) {
            if ($record->status !== PriorityStatus::Pendente) {
                throw ValidationException::withMessages([
                    'status' => 'Este pedido de prioridade já foi analisado.',
                ]);
            }

            $confirmedLevel = $approved ? PriorityLevel::Prioritario : PriorityLevel::Normal;

            $record->update([
                'confirmed_level' => $confirmedLevel,
                'status' => $approved ? PriorityStatus::Confirmada : PriorityStatus::Rejeitada,
                'confirmed_by' => $professionalUser->id,
                'confirmed_at' => now(),
            ]);

            if ($approved) {
                $record->queueTicket->update(['priority_level' => PriorityLevel::Prioritario]);
            }

            $this->auditService->log($professionalUser, 'priority.'.($approved ? 'confirmed' : 'rejected'), $record);

            return $record;
        });
    }

    public function callNext(User $professionalUser, ProfessionalProfile $profile, Service $service): ?QueueTicket
    {
        return DB::transaction(function () use ($professionalUser, $profile, $service) {
            $next = QueueTicket::query()
                ->where('service_id', $service->id)
                ->where('status', QueueTicketStatus::Emitida)
                ->where(function ($query) use ($profile) {
                    $query->where('professional_profile_id', $profile->id)
                        ->orWhereNull('professional_profile_id');
                })
                ->orderByRaw("CASE priority_level WHEN 'prioritario' THEN 0 ELSE 1 END")
                ->orderBy('issued_at')
                ->lockForUpdate()
                ->first();

            if (! $next) {
                return null;
            }

            $next->update([
                'status' => QueueTicketStatus::Chamada,
                'professional_profile_id' => $profile->id,
                'called_at' => now(),
            ]);

            $this->auditService->log($professionalUser, 'queue_ticket.called', $next);

            return $next;
        });
    }

    public function startAttendance(User $actor, QueueTicket $ticket): QueueTicket
    {
        return DB::transaction(function () use ($actor, $ticket) {
            if ($ticket->status !== QueueTicketStatus::Chamada) {
                throw ValidationException::withMessages([
                    'status' => 'Esta senha não está no estado "Chamada".',
                ]);
            }

            $ticket->update(['status' => QueueTicketStatus::EmAtendimento, 'started_at' => now()]);

            $this->auditService->log($actor, 'queue_ticket.started', $ticket);

            return $ticket;
        });
    }

    public function finishAttendance(User $actor, QueueTicket $ticket): QueueTicket
    {
        return DB::transaction(function () use ($actor, $ticket) {
            if ($ticket->status !== QueueTicketStatus::EmAtendimento) {
                throw ValidationException::withMessages([
                    'status' => 'Esta senha não está em atendimento.',
                ]);
            }

            $ticket->update(['status' => QueueTicketStatus::Concluida, 'finished_at' => now()]);

            if ($ticket->appointment_id) {
                $ticket->appointment->update(['status' => AppointmentStatus::Concluido]);
            }

            $this->auditService->log($actor, 'queue_ticket.finished', $ticket);

            return $ticket;
        });
    }

    public function markNoShow(User $actor, QueueTicket $ticket): QueueTicket
    {
        return DB::transaction(function () use ($actor, $ticket) {
            if ($ticket->status !== QueueTicketStatus::Chamada) {
                throw ValidationException::withMessages([
                    'status' => 'Só é possível registar desistência depois de a senha ser chamada.',
                ]);
            }

            $ticket->update(['status' => QueueTicketStatus::Desistencia, 'canceled_at' => now()]);

            $this->auditService->log($actor, 'queue_ticket.no_show', $ticket);

            return $ticket;
        });
    }

    public function cancel(User $utente, QueueTicket $ticket): QueueTicket
    {
        return DB::transaction(function () use ($utente, $ticket) {
            if ($ticket->status !== QueueTicketStatus::Emitida) {
                throw ValidationException::withMessages([
                    'status' => 'Esta senha já não pode ser cancelada.',
                ]);
            }

            $ticket->update(['status' => QueueTicketStatus::Cancelada, 'canceled_at' => now()]);

            $this->auditService->log($utente, 'queue_ticket.cancelled', $ticket);

            return $ticket;
        });
    }

    /**
     * @return Collection<int, QueueTicket>
     */
    public function queueForService(Service $service): Collection
    {
        return QueueTicket::query()
            ->where('service_id', $service->id)
            ->whereIn('status', QueueTicketStatus::activos())
            ->orderByRaw("CASE priority_level WHEN 'prioritario' THEN 0 ELSE 1 END")
            ->orderBy('issued_at')
            ->with(['user', 'professionalProfile.user', 'priorityRecord'])
            ->get();
    }

    public function positionOf(QueueTicket $ticket): ?int
    {
        if ($ticket->status !== QueueTicketStatus::Emitida) {
            return null;
        }

        $waiting = $this->queueForService($ticket->service)
            ->filter(fn (QueueTicket $t) => $t->status === QueueTicketStatus::Emitida)
            ->values();

        $index = $waiting->search(fn (QueueTicket $t) => $t->id === $ticket->id);

        return $index === false ? null : $index + 1;
    }

    private function generateTicketNumber(Service $service, \DateTimeInterface $date): string
    {
        $prefix = Str::upper(Str::substr(preg_replace('/[^A-Za-z]/', '', $service->name) ?? 'SV', 0, 2));

        $count = QueueTicket::query()
            ->where('service_id', $service->id)
            ->whereDate('issued_at', $date)
            ->count();

        return sprintf('%s-%03d', $prefix, $count + 1);
    }
}
