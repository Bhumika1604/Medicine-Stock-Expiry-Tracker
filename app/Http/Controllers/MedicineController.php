<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineRequest;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $query = Medicine::query()
            ->with('category')
            ->withStockTotals()
            ->search($request->get('search'))
            ->category($request->get('category_id'))
            ->manufacturer($request->get('manufacturer'));

        // Stock status filter — computed from the aggregated sums, so filter in PHP-friendly SQL via having.
        if ($status = $request->get('stock_status')) {
            $query->having(function ($q) use ($status) {
                match ($status) {
                    'out_of_stock' => $q->havingRaw('COALESCE(total_quantity, 0) <= 0'),
                    'low_stock' => $q->havingRaw('COALESCE(total_quantity, 0) > 0 AND COALESCE(total_quantity, 0) <= COALESCE(total_min_level, 0)'),
                    'in_stock' => $q->havingRaw('COALESCE(total_quantity, 0) > COALESCE(total_min_level, 0)'),
                    default => null,
                };
            });
        }

        if ($expiryStatus = $request->get('expiry_status')) {
            $query->whereHas('batches', function ($b) use ($expiryStatus) {
                match ($expiryStatus) {
                    'expired' => $b->expired(),
                    'near_expiry' => $b->nearExpiry(),
                    'valid' => $b->valid(),
                    default => null,
                };
            });
        }

        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['name' => 'medicines.name', 'created_at' => 'medicines.created_at', 'total_quantity' => 'total_quantity'];
        $query->orderBy($allowedSorts[$sort] ?? 'medicines.name', $direction === 'desc' ? 'desc' : 'asc');

        $medicines = $query->paginate(12)->withQueryString();

        $categories = Category::active()->orderBy('name')->get();
        $manufacturers = Medicine::query()->select('manufacturer')->distinct()->orderBy('manufacturer')->pluck('manufacturer');

        return view('medicines.index', compact('medicines', 'categories', 'manufacturers'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('medicines.create', compact('categories'));
    }

    public function store(StoreMedicineRequest $request): RedirectResponse
    {
        $medicine = Medicine::create($request->validated());

        return redirect()->route('medicines.show', $medicine)->with('success', 'Medicine added successfully.');
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load(['category', 'batches' => fn ($q) => $q->orderBy('expiry_date')]);

        $summary = [
            'total_quantity' => $medicine->batches->sum('quantity'),
            'batch_count' => $medicine->batches->count(),
            'low_stock_qty' => $medicine->batches->filter(fn ($b) => $b->getStockStatus() === 'LOW STOCK')->sum('quantity'),
            'expired_qty' => $medicine->batches->filter(fn ($b) => $b->getExpiryStatus() === 'EXPIRED')->sum('quantity'),
            'near_expiry_qty' => $medicine->batches->filter(fn ($b) => $b->getExpiryStatus() === 'NEAR EXPIRY')->sum('quantity'),
        ];

        return view('medicines.show', compact('medicine', 'summary'));
    }

    public function edit(Medicine $medicine): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('medicines.edit', compact('medicine', 'categories'));
    }

    public function update(StoreMedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $medicine->update($request->validated());

        return redirect()->route('medicines.show', $medicine)->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $medicine->delete(); // batches cascade-delete at the DB level; medicine itself is soft-deleted

        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
