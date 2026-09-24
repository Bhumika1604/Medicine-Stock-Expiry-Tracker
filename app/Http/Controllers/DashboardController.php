<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\StockTransaction;
use App\Support\Settings;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $nearExpiryDays = Settings::get('near_expiry_days', 30);

        $stats = [
            'total_medicines' => Medicine::count(),
            'total_batches' => Batch::count(),
            'total_stock' => (int) Batch::sum('quantity'),
            'low_stock' => Batch::lowStock()->count(),
            'out_of_stock' => Batch::outOfStock()->count(),
            'near_expiry' => Batch::nearExpiry($nearExpiryDays)->count(),
            'expired' => Batch::expired()->count(),
            'categories' => Category::count(),
        ];

        // Stock by category (for chart)
        $stockByCategory = Category::query()
            ->select('categories.name')
            ->selectRaw('COALESCE(SUM(batches.quantity), 0) as total_quantity')
            ->leftJoin('medicines', 'medicines.category_id', '=', 'categories.id')
            ->leftJoin('batches', 'batches.medicine_id', '=', 'medicines.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_quantity')
            ->get();

        // Expiry status breakdown
        $expiryBreakdown = [
            'valid' => Batch::valid($nearExpiryDays)->count(),
            'near_expiry' => $stats['near_expiry'],
            'expired' => $stats['expired'],
        ];

        // Stock status breakdown
        $stockBreakdown = [
            'in_stock' => Batch::inStock()->count(),
            'low_stock' => $stats['low_stock'],
            'out_of_stock' => $stats['out_of_stock'],
        ];

        $alerts = [];
        if ($stats['near_expiry'] > 0) {
            $alerts[] = ['type' => 'warning', 'text' => "{$stats['near_expiry']} batch(es) are near expiry.", 'url' => route('expiry-alerts.index', ['tab' => 'near_expiry'])];
        }
        if ($stats['expired'] > 0) {
            $alerts[] = ['type' => 'danger', 'text' => "{$stats['expired']} batch(es) have expired.", 'url' => route('expiry-alerts.index', ['tab' => 'expired'])];
        }
        if ($stats['out_of_stock'] > 0) {
            $alerts[] = ['type' => 'danger', 'text' => "{$stats['out_of_stock']} batch(es) are out of stock.", 'url' => route('out-of-stock.index')];
        }
        if ($stats['low_stock'] > 0) {
            $alerts[] = ['type' => 'warning', 'text' => "{$stats['low_stock']} batch(es) are below minimum stock level.", 'url' => route('stock.history').'?status=low'];
        }

        $recentActivity = StockTransaction::with('batch.medicine')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'stockByCategory', 'expiryBreakdown', 'stockBreakdown', 'alerts', 'recentActivity'
        ));
    }
}
