<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_profile_page(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get(route('profile.edit'))
            ->assertOk();
    }

    public function test_user_can_update_their_own_name_and_email(): void
    {
        $user = User::factory()->staff()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    public function test_user_cannot_update_email_to_one_already_taken(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->staff()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_change_their_password_with_correct_current_password(): void
    {
        $user = User::factory()->staff()->create(['password' => Hash::make('old-password')]);

        $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_password_change_requires_correct_current_password(): void
    {
        $user = User::factory()->staff()->create(['password' => Hash::make('old-password')]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_profile_update_cannot_change_own_role(): void
    {
        // UpdateProfileRequest only validates name/email — even if a 'role'
        // field is injected in the request, it's never mass-assigned because
        // the controller passes $request->validated() (name/email only).
        $user = User::factory()->staff()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertEquals(User::ROLE_STAFF, $user->fresh()->role);
    }
}
