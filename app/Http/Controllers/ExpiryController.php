<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpiryController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'near_expiry');
        $nearExpiryDays = Settings::get('near_expiry_days', 30);

        $base = Batch::query()->with('medicine.category');

        $batches = match ($tab) {
            'expired' => (clone $base)->expired(),
            'valid' => (clone $base)->valid($nearExpiryDays),
            default => (clone $base)->nearExpiry($nearExpiryDays),
        };

        if ($from = $request->get('from')) {
            $batches->whereDate('expiry_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $batches->whereDate('expiry_date', '<=', $to);
        }

        $batches = $batches->orderBy('expiry_date')->paginate(15)->withQueryString();

        $counts = [
            'near_expiry' => Batch::nearExpiry($nearExpiryDays)->count(),
            'expired' => Batch::expired()->count(),
            'valid' => Batch::valid($nearExpiryDays)->count(),
        ];

        return view('expiry.index', compact('batches', 'tab', 'counts', 'nearExpiryDays'));
    }
}
