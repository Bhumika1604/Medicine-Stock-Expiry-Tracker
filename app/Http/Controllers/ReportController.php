<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Medicine;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    protected function filteredBatches(Request $request)
    {
        $nearExpiryDays = Settings::get('near_expiry_days', 30);

        return Batch::query()
            ->with('medicine.category')
            ->when($request->get('category_id'), fn ($q, $id) => $q->whereHas('medicine', fn ($m) => $m->where('category_id', $id)))
            ->when($request->get('medicine_id'), fn ($q, $id) => $q->where('medicine_id', $id))
            ->when($request->get('from'), fn ($q, $from) => $q->whereDate('expiry_date', '>=', $from))
            ->when($request->get('to'), fn ($q, $to) => $q->whereDate('expiry_date', '<=', $to))
            ->when($request->get('stock_status'), function ($q, $status) {
                match ($status) {
                    'out_of_stock' => $q->outOfStock(),
                    'low_stock' => $q->lowStock(),
                    'in_stock' => $q->inStock(),
                    default => null,
                };
            })
            ->when($request->get('expiry_status'), function ($q, $status) use ($nearExpiryDays) {
                match ($status) {
                    'expired' => $q->expired(),
                    'near_expiry' => $q->nearExpiry($nearExpiryDays),
                    'valid' => $q->valid($nearExpiryDays),
                    default => null,
                };
            });
    }

    public function inventory(Request $request): View
    {
        $batches = $this->filteredBatches($request)->orderBy('medicine_id')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get(['id', 'name']);

        $summary = [
            'total_medicines' => Medicine::count(),
            'total_batches' => Batch::count(),
            'total_quantity' => Batch::sum('quantity'),
            'categories' => Category::count(),
        ];

        return view('reports.inventory', compact('batches', 'categories', 'medicines', 'summary'));
    }

    public function expiry(Request $request): View
    {
        $batches = $this->filteredBatches($request)->orderBy('expiry_date')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get(['id', 'name']);

        return view('reports.expiry', compact('batches', 'categories', 'medicines'));
    }

    public function stock(Request $request): View
    {
        $batches = $this->filteredBatches($request)->orderBy('quantity')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get(['id', 'name']);

        return view('reports.stock', compact('batches', 'categories', 'medicines'));
    }

    /** CSV export — respects the same filters as the on-screen report. */
    public function export(Request $request): Response
    {
        $type = $request->get('type', 'inventory');
        $batches = $this->filteredBatches($request)->orderBy('medicine_id')->get();

        $filename = "report-{$type}-".now()->format('Y-m-d_His').'.csv';

        $callback = function () use ($batches) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Medicine', 'Category', 'Batch Number', 'Manufacturer', 'Manufacturing Date', 'Expiry Date', 'Days Remaining', 'Quantity', 'Min Stock Level', 'Stock Status', 'Expiry Status', 'Purchase Price', 'Selling Price', 'Supplier']);

            foreach ($batches as $batch) {
                fputcsv($handle, [
                    $batch->medicine->name,
                    $batch->medicine->category->name ?? '',
                    $batch->batch_number,
                    $batch->medicine->manufacturer,
                    optional($batch->manufacturing_date)->format('Y-m-d'),
                    $batch->expiry_date->format('Y-m-d'),
                    $batch->getDaysRemaining(),
                    $batch->quantity,
                    $batch->minimum_stock_level,
                    $batch->getStockStatus(),
                    $batch->getExpiryStatus(),
                    $batch->purchase_price,
                    $batch->selling_price,
                    $batch->supplier_name,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
