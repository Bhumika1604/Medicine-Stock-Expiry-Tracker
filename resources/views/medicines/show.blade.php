@extends('layouts.app')
@section('title', $medicine->name)
@section('page-title', 'Medicine Details')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-ink-900">{{ $medicine->name }}</h2>
            <p class="text-sm text-ink-400">{{ $medicine->generic_name }} · {{ $medicine->category->name ?? '-' }}</p>
        </div>
        <div class="flex gap-2">
            @if (auth()->user()->canManageInventory())
                <a href="{{ route('batches.create', $medicine) }}" class="btn-primary">+ Add Batch</a>
                <a href="{{ route('medicines.edit', $medicine) }}" class="btn-secondary">Edit</a>
                <x-delete-form :action="route('medicines.destroy', $medicine)" confirm="Are you sure you want to delete this medicine? All its batches will be removed too." label="Delete" class="btn-danger" />
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
        <div class="card lg:col-span-1">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Medicine Information</p>
            <dl class="text-sm space-y-2.5">
                <div class="flex justify-between"><dt class="text-ink-400">Manufacturer</dt><dd class="font-medium text-ink-800">{{ $medicine->manufacturer }}</dd></div>
                <div class="flex justify-between"><dt class="text-ink-400">Dosage Form</dt><dd class="font-medium text-ink-800">{{ $medicine->dosage_form }}</dd></div>
                <div class="flex justify-between"><dt class="text-ink-400">Strength</dt><dd class="font-medium text-ink-800">{{ $medicine->strength ?: '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-ink-400">Category</dt><dd class="font-medium text-ink-800">{{ $medicine->category->name ?? '-' }}</dd></div>
            </dl>
            @if ($medicine->description)
                <p class="text-sm text-ink-500 mt-4 pt-4 border-t border-ink-100">{{ $medicine->description }}</p>
            @endif
        </div>

        <div class="card lg:col-span-2">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Stock Summary</p>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                <div class="rounded-lg bg-ink-50 p-3"><p class="text-xl font-bold text-ink-900">{{ $summary['total_quantity'] }}</p><p class="text-xs text-ink-400">Total Qty</p></div>
                <div class="rounded-lg bg-ink-50 p-3"><p class="text-xl font-bold text-ink-900">{{ $summary['batch_count'] }}</p><p class="text-xs text-ink-400">Batches</p></div>
                <div class="rounded-lg bg-amber-50 p-3"><p class="text-xl font-bold text-amber-700">{{ $summary['low_stock_qty'] }}</p><p class="text-xs text-ink-400">Low Stock Qty</p></div>
                <div class="rounded-lg bg-amber-50 p-3"><p class="text-xl font-bold text-amber-700">{{ $summary['near_expiry_qty'] }}</p><p class="text-xs text-ink-400">Near Expiry Qty</p></div>
                <div class="rounded-lg bg-red-50 p-3"><p class="text-xl font-bold text-red-700">{{ $summary['expired_qty'] }}</p><p class="text-xs text-ink-400">Expired Qty</p></div>
            </div>
        </div>
    </div>

    <div class="card p-0 overflow-x-auto">
        <div class="px-5 pt-5 pb-3"><p class="font-semibold text-ink-800 text-sm">Batches</p></div>
        @if ($medicine->batches->isEmpty())
            @if (auth()->user()->canManageInventory())
                <x-empty-state title="No batches yet" subtitle="Add a batch to start tracking quantity and expiry for this medicine." action-label="+ Add Batch" :action-url="route('batches.create', $medicine)" />
            @else
                <x-empty-state title="No batches yet" subtitle="Ask an Admin or Pharmacist to add a batch for this medicine." />
            @endif
        @else
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Batch No.</th>
                        <th class="px-4 py-3 text-left">Mfg Date</th>
                        <th class="px-4 py-3 text-left">Expiry Date</th>
                        <th class="px-4 py-3 text-right">Quantity</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-right">Purchase</th>
                        <th class="px-4 py-3 text-right">Selling</th>
                        <th class="px-4 py-3 text-left">Stock</th>
                        <th class="px-4 py-3 text-left">Expiry</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($medicine->batches as $batch)
                        <tr class="table-row-hover" x-data="{ open: false }">
                            <td class="px-4 py-3 font-medium text-ink-800">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ optional($batch->manufacturing_date)->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->expiry_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ $batch->quantity }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->supplier_name ?: '-' }}</td>
                            <td class="px-4 py-3 text-right text-ink-600">{{ $appSettings['currency_symbol'] }}{{ number_format($batch->purchase_price, 2) }}</td>
                            <td class="px-4 py-3 text-right text-ink-600">{{ $appSettings['currency_symbol'] }}{{ number_format($batch->selling_price, 2) }}</td>
                            <td class="px-4 py-3"><x-badge :color="$batch->stockBadgeColor()" :label="$batch->getStockStatus()" /></td>
                            <td class="px-4 py-3"><x-badge :color="$batch->expiryBadgeColor()" :label="$batch->getExpiryStatus()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-3">
                                    <button @click="open = true" class="text-brand-600 hover:text-brand-800 text-sm font-medium">Update Stock</button>
                                    @if (auth()->user()->canManageInventory())
                                        <a href="{{ route('batches.edit', [$medicine, $batch]) }}" class="text-ink-500 hover:text-brand-600 text-sm font-medium">Edit</a>
                                        <x-delete-form :action="route('batches.destroy', [$medicine, $batch])" confirm="Are you sure you want to delete this batch?" />
                                    @endif
                                </div>

                                {{-- Update stock modal --}}
                                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
                                    <div class="relative bg-white rounded-xl2 shadow-2xl p-6 w-full max-w-sm text-left" @click.stop>
                                        <p class="font-semibold text-ink-900 mb-1">Update Stock — {{ $batch->batch_number }}</p>
                                        <p class="text-xs text-ink-400 mb-4">Current quantity: <span class="font-semibold text-ink-700">{{ $batch->quantity }}</span></p>
                                        <form method="POST" action="{{ route('stock.update', $batch) }}" class="space-y-3">
                                            @csrf
                                            <div>
                                                <label class="form-label">Action</label>
                                                <select name="action" class="form-input" required>
                                                    <option value="add">Add Stock</option>
                                                    <option value="remove">Remove Stock</option>
                                                    <option value="adjustment">Set Exact Quantity (Adjustment)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="quantity" min="1" required class="form-input">
                                            </div>
                                            <div>
                                                <label class="form-label">Reason (optional)</label>
                                                <input type="text" name="reason" maxlength="255" class="form-input" placeholder="e.g. New stock received">
                                            </div>
                                            <div class="flex gap-2 pt-1">
                                                <button class="btn-primary flex-1">Save</button>
                                                <button type="button" @click="open = false" class="btn-secondary flex-1">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
