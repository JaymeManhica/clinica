<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProfessionalTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return [
            'name' => 'Ana Maria',
            'email' => 'ana@exemplo.co.mz',
            'phone' => '841234567',
            'password' => 'Segredo123!',
            'password_confirmation' => 'Segredo123!',
            'title' => 'Enfermeira',
            'registration_number' => 'REG-999',
            'services' => [],
            ...$overrides,
        ];
    }

    public function test_admin_can_list_and_open_create_form(): void
    {
        $admin = User::factory()->administrador()->create();

        $this->actingAs($admin)->get(route('admin.profissionais.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.profissionais.create'))->assertOk();
    }

    public function test_admin_can_create_professional_with_services(): void
    {
        $admin = User::factory()->administrador()->create();
        $service = Service::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.profissionais.store'), $this->payload(['services' => [$service->id]]))
            ->assertRedirect(route('admin.profissionais.index'));

        $user = User::query()->where('email', 'ana@exemplo.co.mz')->firstOrFail();
        $this->assertSame(UserRole::Profissional, $user->role);
        $this->assertTrue($user->professionalProfile->services->contains($service));

        $this->post(route('logout'));
        $this->post(route('login.store'), ['identifier' => 'ana@exemplo.co.mz', 'password' => 'Segredo123!'])
            ->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_update_and_toggle_professional(): void
    {
        $admin = User::factory()->administrador()->create();
        $this->actingAs($admin)->post(route('admin.profissionais.store'), $this->payload());
        $profile = ProfessionalProfile::query()->firstOrFail();

        $this->get(route('admin.profissionais.edit', $profile))->assertOk();

        $this->put(route('admin.profissionais.update', $profile), $this->payload([
            'title' => 'Médica',
            'password' => '',
            'password_confirmation' => '',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('Médica', $profile->fresh()->title);

        $this->patch(route('admin.profissionais.estado', $profile));
        $this->assertFalse($profile->user->fresh()->active);
    }

    public function test_non_admin_cannot_access(): void
    {
        $utente = User::factory()->create(['role' => UserRole::Utente]);

        $this->actingAs($utente)->get(route('admin.profissionais.index'))->assertForbidden();
    }
}
