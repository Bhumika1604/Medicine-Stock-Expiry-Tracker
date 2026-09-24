<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tablets', 'description' => 'Oral solid dosage tablets'],
            ['name' => 'Capsules', 'description' => 'Oral solid dosage capsules'],
            ['name' => 'Syrups', 'description' => 'Oral liquid formulations'],
            ['name' => 'Injections', 'description' => 'Injectable formulations'],
            ['name' => 'Creams', 'description' => 'Topical creams'],
            ['name' => 'Ointments', 'description' => 'Topical ointments'],
            ['name' => 'Drops', 'description' => 'Eye, ear and nasal drops'],
            ['name' => 'Powders', 'description' => 'Oral/topical powders'],
            ['name' => 'Vitamins', 'description' => 'Vitamin and mineral supplements'],
            ['name' => 'Antibiotics', 'description' => 'Antibacterial medicines'],
            ['name' => 'Pain Relief', 'description' => 'Analgesics and pain management'],
            ['name' => 'Other', 'description' => 'Miscellaneous medicines'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['name' => $category['name']], $category + ['status' => true]);
        }
    }
}
