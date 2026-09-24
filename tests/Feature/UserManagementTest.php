<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_user_with_a_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Pharmacist Two',
            'email' => 'pharmacist2@example.com',
            'role' => User::ROLE_PHARMACIST,
            'is_active' => 1,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'pharmacist2@example.com', 'role' => User::ROLE_PHARMACIST]);
    }

    public function test_admin_can_update_a_users_role_and_status(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();

        $this->actingAs($admin)->put(route('users.update', $staff), [
            'name' => $staff->name,
            'email' => $staff->email,
            'role' => User::ROLE_PHARMACIST,
            'is_active' => 0,
        ]);

        $staff->refresh();
        $this->assertEquals(User::ROLE_PHARMACIST, $staff->role);
        $this->assertFalse($staff->is_active);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_remaining_admin_account_cannot_be_deleted(): void
    {
        User::query()->delete();
        $onlyAdmin = User::factory()->admin()->create();

        // In practice the only way to reach "delete the last admin" through
        // the UI is by trying to delete yourself while you're the sole admin
        // — since the 'role:admin' route gate means the acting user is always
        // an admin too. That path is blocked by the self-deletion guard.
        $response = $this->actingAs($onlyAdmin)->delete(route('users.destroy', $onlyAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $onlyAdmin->id]);
        $this->assertEquals(1, User::where('role', User::ROLE_ADMIN)->count());
    }

    public function test_admin_can_delete_a_non_admin_user(): void
    {
        $admin = User::factory()->admin()->create();
        $pharmacist = User::factory()->pharmacist()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $pharmacist));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $pharmacist->id]);
    }

    public function test_admin_deleting_another_admin_is_allowed_when_more_than_one_remains(): void
    {
        $admin = User::factory()->admin()->create();
        $secondAdmin = User::factory()->admin()->create();

        // Two admins exist, so removing one still leaves the system with an
        // admin — the "last admin" guard should NOT block this.
        $response = $this->actingAs($admin)->delete(route('users.destroy', $secondAdmin));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $secondAdmin->id]);
        $this->assertEquals(1, User::where('role', User::ROLE_ADMIN)->count());
    }

    public function test_admin_cannot_remove_their_own_admin_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => User::ROLE_STAFF,
            'is_active' => 1,
        ]);

        $this->assertEquals(User::ROLE_ADMIN, $admin->fresh()->role);
    }
}
