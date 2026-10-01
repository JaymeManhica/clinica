<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\QueueController as AdminQueueController;
use App\Http\Controllers\Admin\QueueTheoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\QueueTicketController;
use App\Http\Controllers\Patient\ServiceCatalogController;
use App\Http\Controllers\Professional\AppointmentController as ProfessionalAppointmentController;
use App\Http\Controllers\Professional\QueueController as ProfessionalQueueController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('registo', [RegisteredUserController::class, 'create'])->name('registo');
    Route::post('registo', [RegisteredUserController::class, 'store'])->name('registo.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'account.active'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('perfil', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::put('perfil/password', [ProfileController::class, 'updatePassword'])->name('perfil.password');

    Route::middleware('role:'.UserRole::Utente->value)->prefix('utente')->name('utente.')->group(function () {
        Route::view('dashboard', 'utente.dashboard')->name('dashboard');

        Route::get('servicos', [ServiceCatalogController::class, 'index'])->name('servicos.index');
        Route::get('servicos/{service}', [ServiceCatalogController::class, 'show'])->name('servicos.show');

        Route::get('agendamentos', [PatientAppointmentController::class, 'index'])->name('agendamentos.index');
        Route::get('agendamentos/criar', [PatientAppointmentController::class, 'create'])->name('agendamentos.create');
        Route::get('agendamentos/horarios', [PatientAppointmentController::class, 'horarios'])->name('agendamentos.horarios');
        Route::post('agendamentos', [PatientAppointmentController::class, 'store'])->name('agendamentos.store');
        Route::patch('agendamentos/{appointment}/cancelar', [PatientAppointmentController::class, 'cancel'])->name('agendamentos.cancel');
        Route::patch('agendamentos/{appointment}/checkin', [QueueTicketController::class, 'checkin'])->name('agendamentos.checkin');

        Route::get('senhas', [QueueTicketController::class, 'index'])->name('senhas.index');
        Route::get('senhas/tirar', [QueueTicketController::class, 'createSpontaneous'])->name('senhas.create');
        Route::post('senhas', [QueueTicketController::class, 'storeSpontaneous'])->name('senhas.store');
        Route::post('senhas/{ticket}/prioridade', [QueueTicketController::class, 'requestPriority'])->name('senhas.prioridade');
        Route::patch('senhas/{ticket}/cancelar', [QueueTicketController::class, 'cancel'])->name('senhas.cancel');
    });

    Route::middleware('role:'.UserRole::Profissional->value)->prefix('profissional')->name('profissional.')->group(function () {
        Route::view('dashboard', 'professional.dashboard')->name('dashboard');

        Route::get('agenda', [ProfessionalAppointmentController::class, 'index'])->name('agenda.index');

        Route::get('fila', [ProfessionalQueueController::class, 'index'])->name('fila.index');
        Route::post('fila/chamar', [ProfessionalQueueController::class, 'callNext'])->name('fila.chamar');
        Route::patch('senhas/{ticket}/iniciar', [ProfessionalQueueController::class, 'start'])->name('senhas.iniciar');
        Route::patch('senhas/{ticket}/concluir', [ProfessionalQueueController::class, 'finish'])->name('senhas.concluir');
        Route::patch('senhas/{ticket}/desistencia', [ProfessionalQueueController::class, 'noShow'])->name('senhas.desistencia');
        Route::patch('prioridades/{priority}/confirmar', [ProfessionalQueueController::class, 'confirmPriority'])->name('prioridades.confirmar');
        Route::patch('prioridades/{priority}/rejeitar', [ProfessionalQueueController::class, 'rejectPriority'])->name('prioridades.rejeitar');
    });

    Route::middleware('role:'.UserRole::Administrador->value)->prefix('admin')->name('admin.')->group(function () {
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        Route::get('servicos', [ServiceController::class, 'index'])->name('servicos.index');
        Route::get('servicos/criar', [ServiceController::class, 'create'])->name('servicos.create');
        Route::post('servicos', [ServiceController::class, 'store'])->name('servicos.store');
        Route::get('servicos/{service}/editar', [ServiceController::class, 'edit'])->name('servicos.edit');
        Route::put('servicos/{service}', [ServiceController::class, 'update'])->name('servicos.update');
        Route::patch('servicos/{service}/estado', [ServiceController::class, 'toggleActive'])->name('servicos.estado');

        Route::get('servicos/{service}/horarios', [AvailabilityController::class, 'index'])->name('servicos.horarios.index');
        Route::get('servicos/{service}/horarios/criar', [AvailabilityController::class, 'create'])->name('servicos.horarios.create');
        Route::post('servicos/{service}/horarios', [AvailabilityController::class, 'store'])->name('servicos.horarios.store');
        Route::get('servicos/{service}/horarios/{availability}/editar', [AvailabilityController::class, 'edit'])->name('servicos.horarios.edit');
        Route::put('servicos/{service}/horarios/{availability}', [AvailabilityController::class, 'update'])->name('servicos.horarios.update');
        Route::delete('servicos/{service}/horarios/{availability}', [AvailabilityController::class, 'destroy'])->name('servicos.horarios.destroy');

        Route::get('agendamentos', [AdminAppointmentController::class, 'index'])->name('agendamentos.index');

        Route::get('filas', [AdminQueueController::class, 'index'])->name('filas.index');

        Route::get('indicadores', [QueueTheoryController::class, 'index'])->name('indicadores.index');
        Route::post('indicadores', [QueueTheoryController::class, 'store'])->name('indicadores.store');
    });
});
