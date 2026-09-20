<?php

use App\Enums\MetricType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_metric_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->decimal('lambda', 10, 4)->comment('Taxa média de chegada (utentes/hora)');
            $table->decimal('mu', 10, 4)->comment('Taxa média de atendimento (utentes/hora)');
            $table->decimal('rho', 10, 4)->comment('Taxa de utilização do sistema');
            $table->decimal('l', 10, 4)->comment('Número médio de utentes no sistema');
            $table->decimal('lq', 10, 4)->comment('Número médio de utentes na fila');
            $table->decimal('w', 10, 4)->comment('Tempo médio no sistema (minutos)');
            $table->decimal('wq', 10, 4)->comment('Tempo médio de espera na fila (minutos)');
            $table->string('type')->default(MetricType::Calculado->value);
            $table->timestamps();

            $table->index(['service_id', 'period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_metric_snapshots');
    }
};
