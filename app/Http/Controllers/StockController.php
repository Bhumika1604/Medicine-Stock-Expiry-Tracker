<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStockRequest;
use App\Models\Batch;
use App\Models\Medicine;
use App\Models\StockTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function outOfStock(Request $request): View
    {
        $batches = Batch::query()
            ->with('medicine.category')
            ->outOfStock()
            ->when($request->get('search'), function ($q, $term) {
                $q->whereHas('medicine', fn ($m) => $m->where('name', 'like', "%{$term}%"));
            })
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('stock.out-of-stock', compact('batches'));
    }

    /** Update stock for a specific batch (Add / Remove / Adjustment). */
    public function update(UpdateStockRequest $request, Batch $batch): RedirectResponse
    {
        $data = $request->validated();
        $previous = $batch->quantity;

        if ($data['action'] === 'remove' && $data['quantity'] > $previous) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.'])->withInput();
        }

        DB::transaction(function () use ($batch, $data, $previous) {
            $new = match ($data['action']) {
                'add' => $previous + $data['quantity'],
                'remove' => $previous - $data['quantity'],
                'adjustment' => $data['quantity'],
            };
            $new = max(0, $new);

            $batch->update(['quantity' => $new]);

            StockTransaction::create([
                'batch_id' => $batch->id,
                'user_id' => auth()->id(),
                'transaction_type' => $data['action'] === 'add' ? 'IN' : ($data['action'] === 'remove' ? 'OUT' : 'ADJUSTMENT'),
                'quantity' => abs($new - $previous),
                'previous_quantity' => $previous,
                'new_quantity' => $new,
                'reason' => $data['reason'] ?? null,
            ]);
        });

        return back()->with('success', 'Stock updated successfully.');
    }

    public function history(Request $request): View
    {
        $transactions = StockTransaction::query()
            ->with(['batch.medicine', 'user'])
            ->when($request->get('medicine_id'), fn ($q, $id) => $q->whereHas('batch', fn ($b) => $b->where('medicine_id', $id)))
            ->when($request->get('batch_id'), fn ($q, $id) => $q->where('batch_id', $id))
            ->when($request->get('type'), fn ($q, $type) => $q->where('transaction_type', $type))
            ->when($request->get('from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->get('to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $medicines = Medicine::orderBy('name')->get(['id', 'name']);

        return view('stock.history', compact('transactions', 'medicines'));
    }
}
