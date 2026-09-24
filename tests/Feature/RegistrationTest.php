<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Pharmacy Staff',
            'email' => 'new.staff@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'new.staff@example.com']);
        $user = User::where('email', 'new.staff@example.com')->first();

        // New self-registrations must always land as Staff — never a higher role.
        $this->assertEquals(User::ROLE_STAFF, $user->role);
        $this->assertTrue($user->is_active);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post(route('register.store'), [
            'name' => 'Someone',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_cannot_inject_a_privileged_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Sneaky User',
            'email' => 'sneaky@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // attempted privilege escalation via extra field
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('users', ['email' => 'sneaky@example.com', 'role' => User::ROLE_STAFF]);
    }

    public function test_home_page_is_public(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_authenticated_user_visiting_home_is_redirected_to_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertRedirect(route('dashboard'));
    }
}
