<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBatchRequest;
use App\Models\Batch;
use App\Models\Medicine;
use App\Models\StockTransaction;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function create(Medicine $medicine): View
    {
        return view('batches.create', [
            'medicine' => $medicine,
            'defaultMinStock' => Settings::get('default_min_stock', 10),
        ]);
    }

    public function store(StoreBatchRequest $request, Medicine $medicine): RedirectResponse
    {
        DB::transaction(function () use ($request, $medicine) {
            $batch = $medicine->batches()->create($request->validated());

            if ($batch->quantity > 0) {
                StockTransaction::create([
                    'batch_id' => $batch->id,
                    'user_id' => auth()->id(),
                    'transaction_type' => 'IN',
                    'quantity' => $batch->quantity,
                    'previous_quantity' => 0,
                    'new_quantity' => $batch->quantity,
                    'reason' => 'Initial batch stock on creation',
                ]);
            }
        });

        return redirect()->route('medicines.show', $medicine)->with('success', 'Batch added successfully.');
    }

    public function edit(Medicine $medicine, Batch $batch): View
    {
        return view('batches.edit', compact('medicine', 'batch'));
    }

    public function update(StoreBatchRequest $request, Medicine $medicine, Batch $batch): RedirectResponse
    {
        DB::transaction(function () use ($request, $batch) {
            $data = $request->validated();
            $previousQty = $batch->quantity;
            $batch->update($data);

            // If the quantity was edited directly from this form, log it as an adjustment.
            if ((int) $data['quantity'] !== $previousQty) {
                StockTransaction::create([
                    'batch_id' => $batch->id,
                    'user_id' => auth()->id(),
                    'transaction_type' => 'ADJUSTMENT',
                    'quantity' => abs($data['quantity'] - $previousQty),
                    'previous_quantity' => $previousQty,
                    'new_quantity' => (int) $data['quantity'],
                    'reason' => 'Batch details edited',
                ]);
            }
        });

        return redirect()->route('medicines.show', $medicine)->with('success', 'Batch updated successfully.');
    }

    public function destroy(Medicine $medicine, Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()->route('medicines.show', $medicine)->with('success', 'Batch deleted successfully.');
    }
}
