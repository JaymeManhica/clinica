<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Availability;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->administrador()->create([
            'name' => 'Administrador do Posto',
            'email' => 'admin@minhasaude.co.mz',
            'phone' => '840000000',
        ]);

        $servicos = [
            ['name' => 'Consulta Geral', 'average_duration_minutes' => 20],
            ['name' => 'Vacinação', 'average_duration_minutes' => 10],
            ['name' => 'Consulta Pré-Natal', 'average_duration_minutes' => 25],
        ];

        $services = collect($servicos)->map(fn (array $dados) => Service::query()->create([
            'name' => $dados['name'],
            'description' => null,
            'average_duration_minutes' => $dados['average_duration_minutes'],
            'active' => true,
        ]));

        $services->each(function (Service $service) {
            $user = User::factory()->profissional()->create();

            $profile = ProfessionalProfile::query()->create([
                'user_id' => $user->id,
                'title' => 'Enfermeiro',
                'registration_number' => 'REG-'.$user->id.'-'.$service->id,
            ]);

            $profile->services()->attach($service);

            foreach (range(1, 5) as $diaSemana) {
                Availability::query()->create([
                    'professional_profile_id' => $profile->id,
                    'service_id' => $service->id,
                    'day_of_week' => $diaSemana,
                    'start_time' => '07:30:00',
                    'end_time' => '15:30:00',
                    'active' => true,
                ]);
            }
        });

        User::factory(10)->create([
            'role' => UserRole::Utente,
        ]);

        Setting::query()->create([
            'key' => 'cancelamento_antecedencia_minima_horas',
            'value' => '2',
        ]);
    }
}
