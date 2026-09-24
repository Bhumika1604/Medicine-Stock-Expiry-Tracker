@extends('layouts.app')
@section('title', 'Medicines')
@section('page-title', 'Medicines')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-ink-900">Medicines</h2>
            <p class="text-sm text-ink-400">{{ $medicines->total() }} medicine(s) in inventory</p>
        </div>
        @if (auth()->user()->canManageInventory())
        <a href="{{ route('medicines.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add Medicine
        </a>
        @endif
    </div>

    {{-- Search & filters --}}
    <form method="GET" class="card mb-5 grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
        <div class="md:col-span-2">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, generic, manufacturer, batch..." class="form-input">
        </div>
        <div>
            <label class="form-label">Category</label>
            <select name="category_id" class="form-input">
                <option value="">All</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Stock Status</label>
            <select name="stock_status" class="form-input">
                <option value="">All</option>
                <option value="in_stock" @selected(request('stock_status')=='in_stock')>In Stock</option>
                <option value="low_stock" @selected(request('stock_status')=='low_stock')>Low Stock</option>
                <option value="out_of_stock" @selected(request('stock_status')=='out_of_stock')>Out of Stock</option>
            </select>
        </div>
        <div>
            <label class="form-label">Expiry Status</label>
            <select name="expiry_status" class="form-input">
                <option value="">All</option>
                <option value="valid" @selected(request('expiry_status')=='valid')>Valid</option>
                <option value="near_expiry" @selected(request('expiry_status')=='near_expiry')>Near Expiry</option>
                <option value="expired" @selected(request('expiry_status')=='expired')>Expired</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="btn-primary w-full">Filter</button>
            @if (request()->anyFilled(['search','category_id','stock_status','expiry_status']))
                <a href="{{ route('medicines.index') }}" class="btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    @if ($medicines->isEmpty())
        <div class="card">
            @if (auth()->user()->canManageInventory())
                <x-empty-state title="No medicines found" subtitle="Try adjusting your filters, or add your first medicine to the inventory." action-label="+ Add Medicine" :action-url="route('medicines.create')" />
            @else
                <x-empty-state title="No medicines found" subtitle="Try adjusting your filters. Ask an Admin or Pharmacist to add medicines to the inventory." />
            @endif
        </div>
    @else
        <div class="card overflow-x-auto p-0">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-ink-800">Medicine Name</a>
                        </th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Manufacturer</th>
                        <th class="px-4 py-3 text-left">Dosage Form</th>
                        <th class="px-4 py-3 text-right">Total Stock</th>
                        <th class="px-4 py-3 text-right">Batches</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($medicines as $medicine)
                        @php
                            $qty = $medicine->total_quantity ?? 0;
                            $minLevel = $medicine->total_min_level ?? 0;
                            $status = $qty <= 0 ? 'OUT OF STOCK' : ($qty <= $minLevel ? 'LOW STOCK' : 'IN STOCK');
                            $color = $qty <= 0 ? 'red' : ($qty <= $minLevel ? 'orange' : 'green');
                        @endphp
                        <tr class="table-row-hover">
                            <td class="px-4 py-3 text-ink-400">#{{ $medicine->id }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('medicines.show', $medicine) }}" class="font-medium text-ink-900 hover:text-brand-600">{{ $medicine->name }}</a>
                                <p class="text-xs text-ink-400">{{ $medicine->generic_name }}</p>
                            </td>
                            <td class="px-4 py-3 text-ink-600">{{ $medicine->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $medicine->manufacturer }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $medicine->dosage_form }} @if($medicine->strength) <span class="text-ink-400">({{ $medicine->strength }})</span>@endif</td>
                            <td class="px-4 py-3 text-right font-medium">{{ $qty }}</td>
                            <td class="px-4 py-3 text-right">{{ $medicine->batches_count }}</td>
                            <td class="px-4 py-3"><x-badge :color="$color" :label="$status" /></td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('medicines.show', $medicine) }}" class="text-ink-500 hover:text-brand-600 text-sm font-medium">View</a>
                                    @if (auth()->user()->canManageInventory())
                                        <a href="{{ route('medicines.edit', $medicine) }}" class="text-ink-500 hover:text-brand-600 text-sm font-medium">Edit</a>
                                        <x-delete-form :action="route('medicines.destroy', $medicine)" confirm="Are you sure you want to delete this medicine? All its batches will be removed too." />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $medicines->links() }}</div>
    @endif
@endsection
