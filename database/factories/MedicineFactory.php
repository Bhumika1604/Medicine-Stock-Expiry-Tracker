<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    protected $model = Medicine::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->word().' '.$this->faker->randomNumber(3).'mg',
            'generic_name' => $this->faker->word(),
            'manufacturer' => $this->faker->company(),
            'dosage_form' => $this->faker->randomElement(Medicine::DOSAGE_FORMS),
            'strength' => $this->faker->randomNumber(3).'mg',
            'description' => $this->faker->sentence(),
        ];
    }
}
