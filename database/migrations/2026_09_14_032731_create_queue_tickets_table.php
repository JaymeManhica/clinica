<?php

use App\Enums\PriorityLevel;
use App\Enums\QueueTicketStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number');
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professional_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('origin');
            $table->string('status')->default(QueueTicketStatus::Emitida->value);
            $table->string('priority_level')->default(PriorityLevel::Normal->value);
            $table->dateTime('issued_at');
            $table->dateTime('called_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->timestamps();

            $table->index(['service_id', 'status']);
            $table->index(['service_id', 'issued_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_tickets');
    }
};
