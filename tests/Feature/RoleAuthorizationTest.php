<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_create_a_medicine(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get(route('medicines.create'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('medicines.store'), ['name' => 'Should Fail'])
            ->assertForbidden();
    }

  public function test_pharmacist_can_create_a_medicine(): void
{
    $pharmacist = User::factory()->pharmacist()->create();

    $this->actingAs($pharmacist)
        ->get(route('medicines.create'))
        ->assertOk();
}

    public function test_staff_can_view_medicines_list(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get(route('medicines.index'))
            ->assertOk();
    }

    public function test_staff_cannot_access_categories(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get(route('categories.index'))
            ->assertForbidden();
    }

    public function test_pharmacist_cannot_access_categories(): void
    {
        $this->actingAs(User::factory()->pharmacist()->create())
            ->get(route('categories.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_categories(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('categories.index'))
            ->assertOk();
    }

    public function test_staff_cannot_access_reports(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get(route('reports.index'))
            ->assertForbidden();
    }

    public function test_pharmacist_can_access_reports(): void
    {
        $this->actingAs(User::factory()->pharmacist()->create())
            ->get(route('reports.index'))
            ->assertOk();
    }

    public function test_only_admin_can_access_settings(): void
    {
        $this->actingAs(User::factory()->pharmacist()->create())
            ->get(route('settings.edit'))
            ->assertForbidden();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('settings.edit'))
            ->assertOk();
    }

    public function test_only_admin_can_access_user_management(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get(route('users.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('users.index'))
            ->assertOk();
    }

    public function test_all_roles_can_update_stock(): void
    {
        $medicine = Medicine::factory()->create();
        $batch = $medicine->batches()->create([
            'batch_number' => 'ROLE001',
            'expiry_date' => now()->addYear(),
            'quantity' => 50,
            'minimum_stock_level' => 5,
        ]);

        $this->actingAs(User::factory()->staff()->create())
            ->post(route('stock.update', $batch), ['action' => 'add', 'quantity' => 10])
            ->assertSessionHasNoErrors();

        $this->assertEquals(60, $batch->fresh()->quantity);
    }

    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
