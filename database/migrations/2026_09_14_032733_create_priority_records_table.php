<?php

use App\Enums\PriorityStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('priority_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_ticket_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('requested_level');
            $table->string('confirmed_level')->nullable();
            $table->text('reason');
            $table->string('status')->default(PriorityStatus::Pendente->value);
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priority_records');
    }
};
