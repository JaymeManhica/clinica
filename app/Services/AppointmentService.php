<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @return array<int, string>
     */
    public function availableSlots(Service $service, ProfessionalProfile $professional, CarbonImmutable $date): array
    {
        $windows = Availability::query()
            ->where('professional_profile_id', $professional->id)
            ->where('service_id', $service->id)
            ->where('day_of_week', $date->dayOfWeek)
            ->where('active', true)
            ->get();

        if ($windows->isEmpty()) {
            return [];
        }

        $bookedTimes = Appointment::query()
            ->where('professional_profile_id', $professional->id)
            ->whereDate('scheduled_at', $date->toDateString())
            ->where('status', '!=', AppointmentStatus::Cancelado)
            ->get('scheduled_at')
            ->map(fn (Appointment $appointment) => $appointment->scheduled_at->format('H:i'))
            ->all();

        $duration = $service->average_duration_minutes;
        $slots = [];

        foreach ($windows as $window) {
            $current = CarbonImmutable::parse($date->toDateString().' '.$window->start_time);
            $windowEnd = CarbonImmutable::parse($date->toDateString().' '.$window->end_time);

            while ($current->addMinutes($duration)->lessThanOrEqualTo($windowEnd)) {
                $label = $current->format('H:i');

                if (! in_array($label, $bookedTimes, true) && $current->isFuture()) {
                    $slots[] = $label;
                }

                $current = $current->addMinutes($duration);
            }
        }

        sort($slots);

        return array_values(array_unique($slots));
    }

    public function book(User $utente, Service $service, ProfessionalProfile $professional, string $date, string $time): Appointment
    {
        return DB::transaction(function () use ($utente, $service, $professional, $date, $time) {
            $scheduledAt = Carbon::parse($date.' '.$time);

            $conflict = Appointment::query()
                ->where('professional_profile_id', $professional->id)
                ->where('scheduled_at', $scheduledAt)
                ->where('status', '!=', AppointmentStatus::Cancelado)
                ->lockForUpdate()
                ->exists();

            $availableSlots = $this->availableSlots($service, $professional, CarbonImmutable::parse($date));

            if ($conflict || ! in_array($time, $availableSlots, true)) {
                throw ValidationException::withMessages([
                    'time' => 'O horário seleccionado já não está disponível. Escolha outro horário.',
                ]);
            }

            $appointment = Appointment::query()->create([
                'user_id' => $utente->id,
                'professional_profile_id' => $professional->id,
                'service_id' => $service->id,
                'scheduled_at' => $scheduledAt,
                'status' => AppointmentStatus::Agendado,
            ]);

            $this->auditService->log($utente, 'appointment.booked', $appointment, [], [
                'scheduled_at' => $scheduledAt->toDateTimeString(),
            ]);

            return $appointment;
        });
    }

    public function cancel(User $actor, Appointment $appointment, ?string $reason): Appointment
    {
        return DB::transaction(function () use ($actor, $appointment, $reason) {
            if (! $appointment->isCancelable()) {
                throw ValidationException::withMessages([
                    'status' => 'Este agendamento já não pode ser cancelado (estado inválido ou fora do prazo mínimo de antecedência).',
                ]);
            }

            $old = ['status' => $appointment->status->value];

            $appointment->update([
                'status' => AppointmentStatus::Cancelado,
                'cancellation_reason' => $reason,
            ]);

            $this->auditService->log($actor, 'appointment.cancelled', $appointment, $old, [
                'status' => AppointmentStatus::Cancelado->value,
                'cancellation_reason' => $reason,
            ]);

            return $appointment;
        });
    }
}
