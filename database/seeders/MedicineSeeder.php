<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\StockTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class MedicineSeeder extends Seeder
{
    protected array $suppliers = [
        'Sun Pharma Distributors', 'Cipla Trading Co.', 'Mankind Wholesale',
        'Zydus Healthcare Supply', 'Dr. Reddy\'s Agency', 'Lupin Distributors',
        'Alkem Pharma Traders', 'Torrent Supply Chain',
    ];

    /**
     * name, generic_name, category, manufacturer, dosage_form, strength
     */
    protected function medicines(): array
    {
        return [
            ['Paracetamol 500mg', 'Paracetamol', 'Tablets', 'GSK Pharmaceuticals', 'Tablet', '500mg'],
            ['Azithromycin 500mg', 'Azithromycin', 'Antibiotics', 'Cipla Ltd', 'Tablet', '500mg'],
            ['Cetirizine 10mg', 'Cetirizine Hydrochloride', 'Tablets', 'Dr. Reddy\'s Labs', 'Tablet', '10mg'],
            ['Amoxicillin 500mg', 'Amoxicillin', 'Antibiotics', 'Sun Pharma', 'Capsule', '500mg'],
            ['Ibuprofen 400mg', 'Ibuprofen', 'Pain Relief', 'Abbott India', 'Tablet', '400mg'],
            ['Pantoprazole 40mg', 'Pantoprazole', 'Tablets', 'Alkem Laboratories', 'Tablet', '40mg'],
            ['Omeprazole 20mg', 'Omeprazole', 'Capsules', 'Lupin Ltd', 'Capsule', '20mg'],
            ['Metformin 500mg', 'Metformin Hydrochloride', 'Tablets', 'USV Pvt Ltd', 'Tablet', '500mg'],
            ['ORS Sachet', 'Oral Rehydration Salts', 'Powders', 'FDC Ltd', 'Powder', '21.8g'],
            ['Vitamin C 500mg', 'Ascorbic Acid', 'Vitamins', 'Mankind Pharma', 'Tablet', '500mg'],
            ['Calcium + D3 Tablets', 'Calcium Carbonate', 'Vitamins', 'Zydus Cadila', 'Tablet', '500mg'],
            ['Cough Syrup', 'Dextromethorphan', 'Syrups', 'Torrent Pharma', 'Syrup', '100ml'],
            ['Diclofenac Gel', 'Diclofenac Diethylamine', 'Creams', 'Novartis India', 'Cream', '1% w/w'],
            ['Betamethasone Ointment', 'Betamethasone', 'Ointments', 'Glenmark Pharma', 'Ointment', '0.05%'],
            ['Ciprofloxacin Eye Drops', 'Ciprofloxacin', 'Drops', 'Ajanta Pharma', 'Drops', '0.3%'],
            ['Insulin Glargine Injection', 'Insulin Glargine', 'Injections', 'Biocon Ltd', 'Injection', '100IU/ml'],
            ['Ceftriaxone Injection', 'Ceftriaxone Sodium', 'Injections', 'Cipla Ltd', 'Injection', '1g'],
            ['Losartan 50mg', 'Losartan Potassium', 'Tablets', 'Sun Pharma', 'Tablet', '50mg'],
            ['Amlodipine 5mg', 'Amlodipine Besylate', 'Tablets', 'Cadila Healthcare', 'Tablet', '5mg'],
            ['Levocetirizine 5mg', 'Levocetirizine', 'Tablets', 'Dr. Reddy\'s Labs', 'Tablet', '5mg'],
            ['Multivitamin Syrup', 'Multivitamin', 'Syrups', 'Mankind Pharma', 'Syrup', '200ml'],
            ['Ranitidine 150mg', 'Ranitidine', 'Tablets', 'GSK Pharmaceuticals', 'Tablet', '150mg'],
            ['Salbutamol Inhaler', 'Salbutamol', 'Other', 'Cipla Ltd', 'Other', '100mcg'],
            ['Povidone Iodine Ointment', 'Povidone Iodine', 'Ointments', 'Win-Medicare', 'Ointment', '5% w/w'],
        ];
    }

    public function run(): void
    {
        $admin = User::first();
        $categories = Category::pluck('id', 'name');
        $today = Carbon::today();

        foreach ($this->medicines() as $i => [$name, $generic, $categoryName, $manufacturer, $form, $strength]) {
            $medicine = Medicine::create([
                'category_id' => $categories[$categoryName] ?? $categories->first(),
                'name' => $name,
                'generic_name' => $generic,
                'manufacturer' => $manufacturer,
                'dosage_form' => $form,
                'strength' => $strength,
                'description' => "{$name} — standard {$form} formulation used for common indications related to {$generic}.",
            ]);

            // Deterministically vary scenarios across the medicine list so the
            // dashboard/demo has a realistic mix of every status.
            $scenario = $i % 6;

            $batchesForScenario = match ($scenario) {
                0 => [ // healthy in-stock, single batch, valid expiry
                    ['qty' => 300, 'min' => 30, 'expiryDays' => 400],
                ],
                1 => [ // low stock
                    ['qty' => 8, 'min' => 20, 'expiryDays' => 250],
                ],
                2 => [ // out of stock
                    ['qty' => 0, 'min' => 15, 'expiryDays' => 180],
                ],
                3 => [ // near expiry (within 30 days)
                    ['qty' => 120, 'min' => 20, 'expiryDays' => 12],
                ],
                4 => [ // expired
                    ['qty' => 40, 'min' => 10, 'expiryDays' => -15],
                ],
                default => [ // multiple batches: mixed statuses for the same medicine
                    ['qty' => 150, 'min' => 20, 'expiryDays' => 500],
                    ['qty' => 5, 'min' => 25, 'expiryDays' => 20],
                ],
            };

            foreach ($batchesForScenario as $j => $b) {
                $expiry = $today->copy()->addDays($b['expiryDays']);
                $manufacturing = $expiry->copy()->subMonths(rand(12, 24));

                $batch = Batch::create([
                    'medicine_id' => $medicine->id,
                    'batch_number' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)).sprintf('%03d', ($i + 1) * 10 + $j),
                    'manufacturing_date' => $manufacturing,
                    'expiry_date' => $expiry,
                    'quantity' => $b['qty'],
                    'minimum_stock_level' => $b['min'],
                    'purchase_price' => $price = rand(20, 400) + (rand(0, 99) / 100),
                    'selling_price' => round($price * 1.35, 2),
                    'supplier_name' => Arr::random($this->suppliers),
                ]);

                // Initial stock-in transaction
                $received = $b['qty'] + rand(0, 30);
                StockTransaction::create([
                    'batch_id' => $batch->id,
                    'user_id' => $admin?->id,
                    'transaction_type' => 'IN',
                    'quantity' => $received,
                    'previous_quantity' => 0,
                    'new_quantity' => $received,
                    'reason' => 'Initial stock received from supplier',
                    'created_at' => $manufacturing->copy()->addDays(2),
                    'updated_at' => $manufacturing->copy()->addDays(2),
                ]);

                // If final quantity differs from what was received, log the dispatch/sale that brought it down.
                if ($received !== $b['qty']) {
                    StockTransaction::create([
                        'batch_id' => $batch->id,
                        'user_id' => $admin?->id,
                        'transaction_type' => 'OUT',
                        'quantity' => $received - $b['qty'],
                        'previous_quantity' => $received,
                        'new_quantity' => $b['qty'],
                        'reason' => 'Dispensed to patients / retail sale',
                        'created_at' => $manufacturing->copy()->addDays(10),
                        'updated_at' => $manufacturing->copy()->addDays(10),
                    ]);
                }
            }
        }
    }
}
