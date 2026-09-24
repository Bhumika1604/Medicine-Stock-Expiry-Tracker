<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_batch_can_be_created_for_a_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post(route('batches.store', $medicine), [
            'batch_number' => 'PCM001',
            'manufacturing_date' => now()->subMonths(2)->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'quantity' => 100,
            'minimum_stock_level' => 10,
            'purchase_price' => 20,
            'selling_price' => 30,
            'supplier_name' => 'ABC Distributors',
        ]);

        $this->assertDatabaseHas('batches', ['medicine_id' => $medicine->id, 'batch_number' => 'PCM001', 'quantity' => 100]);
        $response->assertRedirect(route('medicines.show', $medicine));

        // Initial stock-in transaction should be logged (Rule 9).
        $this->assertDatabaseHas('stock_transactions', ['transaction_type' => 'IN', 'new_quantity' => 100]);
    }

    public function test_manufacturing_date_cannot_be_after_expiry_date(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post(route('batches.store', $medicine), [
            'batch_number' => 'PCM002',
            'manufacturing_date' => now()->addMonths(2)->toDateString(), // after expiry
            'expiry_date' => now()->toDateString(),
            'quantity' => 50,
            'minimum_stock_level' => 5,
        ]);

        $response->assertSessionHasErrors(['manufacturing_date']);
        $this->assertDatabaseMissing('batches', ['batch_number' => 'PCM002']);
    }

    public function test_negative_quantity_is_rejected(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post(route('batches.store', $medicine), [
            'batch_number' => 'PCM003',
            'expiry_date' => now()->addYear()->toDateString(),
            'quantity' => -10,
            'minimum_stock_level' => 5,
        ]);

        $response->assertSessionHasErrors(['quantity']);
        $this->assertDatabaseMissing('batches', ['batch_number' => 'PCM003']);
    }

    public function test_batch_number_must_be_unique_per_medicine(): void
    {
        $medicine = Medicine::factory()->create();
        $medicine->batches()->create([
            'batch_number' => 'DUPLICATE',
            'expiry_date' => now()->addYear(),
            'quantity' => 10,
            'minimum_stock_level' => 5,
        ]);

        $response = $this->actingAs(User::factory()->create())->post(route('batches.store', $medicine), [
            'batch_number' => 'DUPLICATE',
            'expiry_date' => now()->addYear()->toDateString(),
            'quantity' => 20,
            'minimum_stock_level' => 5,
        ]);

        $response->assertSessionHasErrors(['batch_number']);
    }
}
