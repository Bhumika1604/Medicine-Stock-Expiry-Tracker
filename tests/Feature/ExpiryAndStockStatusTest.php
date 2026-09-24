<?php

namespace Tests\Feature;

use App\Models\Batch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpiryAndStockStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_batch_expiring_within_threshold_is_detected_as_near_expiry(): void
    {
        $batch = Batch::factory()->create(['expiry_date' => now()->addDays(10)]);

        $this->assertEquals('NEAR EXPIRY', $batch->getExpiryStatus());
        $this->assertTrue(Batch::nearExpiry()->whereKey($batch->id)->exists());
    }

    public function test_batch_past_expiry_date_is_detected_as_expired(): void
    {
        $batch = Batch::factory()->create(['expiry_date' => now()->subDays(5)]);

        $this->assertEquals('EXPIRED', $batch->getExpiryStatus());
        $this->assertTrue(Batch::expired()->whereKey($batch->id)->exists());
    }

    public function test_batch_with_zero_quantity_is_detected_as_out_of_stock(): void
    {
        $batch = Batch::factory()->create(['quantity' => 0]);

        $this->assertEquals('OUT OF STOCK', $batch->getStockStatus());
        $this->assertTrue(Batch::outOfStock()->whereKey($batch->id)->exists());
    }

    public function test_batch_at_or_below_minimum_level_is_detected_as_low_stock(): void
    {
        $batch = Batch::factory()->create(['quantity' => 5, 'minimum_stock_level' => 10]);

        $this->assertEquals('LOW STOCK', $batch->getStockStatus());
        $this->assertTrue(Batch::lowStock()->whereKey($batch->id)->exists());
    }

    public function test_batch_above_minimum_level_and_far_from_expiry_is_valid_and_in_stock(): void
    {
        $batch = Batch::factory()->create([
            'quantity' => 200,
            'minimum_stock_level' => 10,
            'expiry_date' => now()->addYear(),
        ]);

        $this->assertEquals('IN STOCK', $batch->getStockStatus());
        $this->assertEquals('VALID', $batch->getExpiryStatus());
    }
}
