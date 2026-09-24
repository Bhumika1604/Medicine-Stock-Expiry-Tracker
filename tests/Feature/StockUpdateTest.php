<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_can_be_added(): void
    {
        $batch = Batch::factory()->create(['quantity' => 100]);

        $this->actingAs(User::factory()->create())->post(route('stock.update', $batch), [
            'action' => 'add',
            'quantity' => 50,
        ]);

        $this->assertEquals(150, $batch->fresh()->quantity);
        $this->assertDatabaseHas('stock_transactions', ['batch_id' => $batch->id, 'transaction_type' => 'IN', 'previous_quantity' => 100, 'new_quantity' => 150]);
    }

    public function test_stock_can_be_removed(): void
    {
        $batch = Batch::factory()->create(['quantity' => 100]);

        $this->actingAs(User::factory()->create())->post(route('stock.update', $batch), [
            'action' => 'remove',
            'quantity' => 20,
        ]);

        $this->assertEquals(80, $batch->fresh()->quantity);
        $this->assertDatabaseHas('stock_transactions', ['batch_id' => $batch->id, 'transaction_type' => 'OUT', 'previous_quantity' => 100, 'new_quantity' => 80]);
    }

    public function test_removing_more_stock_than_available_is_prevented(): void
    {
        $batch = Batch::factory()->create(['quantity' => 10]);

        $response = $this->actingAs(User::factory()->create())->post(route('stock.update', $batch), [
            'action' => 'remove',
            'quantity' => 20,
        ]);

        $response->assertSessionHasErrors(['quantity']);
        $this->assertEquals(10, $batch->fresh()->quantity); // unchanged
        $this->assertDatabaseMissing('stock_transactions', ['batch_id' => $batch->id, 'transaction_type' => 'OUT']);
    }

    public function test_stock_cannot_go_negative(): void
    {
        $batch = Batch::factory()->create(['quantity' => 5]);

        $this->actingAs(User::factory()->create())->post(route('stock.update', $batch), [
            'action' => 'remove',
            'quantity' => 5,
        ]);

        $this->assertEquals(0, $batch->fresh()->quantity);
        $this->assertGreaterThanOrEqual(0, $batch->fresh()->quantity);
    }
}
