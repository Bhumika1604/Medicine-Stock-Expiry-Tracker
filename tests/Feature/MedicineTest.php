<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineTest extends TestCase
{
    use RefreshDatabase;

    protected function actingUser(): User
    {
        return User::factory()->create();
    }

    public function test_medicine_can_be_created(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->actingUser())->post(route('medicines.store'), [
            'name' => 'Paracetamol 500mg',
            'generic_name' => 'Paracetamol',
            'category_id' => $category->id,
            'manufacturer' => 'GSK',
            'dosage_form' => 'Tablet',
            'strength' => '500mg',
            'description' => 'Pain relief tablet',
        ]);

        $this->assertDatabaseHas('medicines', ['name' => 'Paracetamol 500mg']);
        $response->assertRedirect(route('medicines.show', Medicine::first()));
    }

    public function test_medicine_creation_requires_name_category_and_manufacturer(): void
    {
        $response = $this->actingAs($this->actingUser())->post(route('medicines.store'), []);

        $response->assertSessionHasErrors(['name', 'category_id', 'manufacturer', 'dosage_form']);
    }

    public function test_medicine_can_be_updated(): void
    {
        $medicine = Medicine::factory()->create(['name' => 'Old Name']);

        $this->actingAs($this->actingUser())->put(route('medicines.update', $medicine), [
            'name' => 'Updated Name',
            'category_id' => $medicine->category_id,
            'manufacturer' => $medicine->manufacturer,
            'dosage_form' => $medicine->dosage_form,
        ]);

        $this->assertDatabaseHas('medicines', ['id' => $medicine->id, 'name' => 'Updated Name']);
    }

    public function test_medicine_can_be_deleted(): void
    {
        $medicine = Medicine::factory()->create();

        $this->actingAs($this->actingUser())->delete(route('medicines.destroy', $medicine));

        $this->assertSoftDeleted('medicines', ['id' => $medicine->id]);
    }
}
