<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition(): array
    {
        return [
            'medicine_id' => Medicine::factory(),
            'batch_number' => strtoupper($this->faker->unique()->bothify('BATCH-####')),
            'manufacturing_date' => now()->subMonths(6),
            'expiry_date' => now()->addYear(),
            'quantity' => 100,
            'minimum_stock_level' => 10,
            'purchase_price' => 50,
            'selling_price' => 75,
            'supplier_name' => $this->faker->company(),
        ];
    }
}
