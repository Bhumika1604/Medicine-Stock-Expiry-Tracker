@extends('layouts.app')
@section('title', 'Out of Stock')
@section('page-title', 'Out of Stock')

@section('content')
    <div class="mb-5">
        <h2 class="text-lg font-bold text-ink-900">Out-of-Stock Batches</h2>
        <p class="text-sm text-ink-400">{{ $batches->total() }} batch(es) currently have zero quantity.</p>
    </div>

    <form method="GET" class="card mb-5 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by medicine name..." class="form-input">
        <button class="btn-primary shrink-0">Search</button>
    </form>

    @if ($batches->isEmpty())
        <div class="card">
            <x-empty-state title="Nothing is out of stock" subtitle="Great! All tracked batches currently have available quantity." />
        </div>
    @else
        <div class="card p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Medicine</th>
                        <th class="px-4 py-3 text-left">Batch No.</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-left">Expiry Date</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($batches as $batch)
                        <tr class="table-row-hover" x-data="{ open: false }">
                            <td class="px-4 py-3 font-medium text-ink-800"><a href="{{ route('medicines.show', $batch->medicine) }}" class="hover:text-brand-600">{{ $batch->medicine->name }}</a></td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->medicine->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->supplier_name ?: '-' }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->expiry_date->format('d M Y') }}</td>
                            <td class="px-4 py-3"><x-badge color="red" label="OUT OF STOCK" /></td>
                            <td class="px-4 py-3 text-right">
                                <button @click="open = true" class="btn-primary !py-1.5 !px-3 text-xs">Update Stock</button>
                                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
                                    <div class="relative bg-white rounded-xl2 shadow-2xl p-6 w-full max-w-sm text-left" @click.stop>
                                        <p class="font-semibold text-ink-900 mb-4">Update Stock — {{ $batch->batch_number }}</p>
                                        <form method="POST" action="{{ route('stock.update', $batch) }}" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="action" value="add">
                                            <div>
                                                <label class="form-label">Quantity to Add</label>
                                                <input type="number" name="quantity" min="1" required class="form-input">
                                            </div>
                                            <div>
                                                <label class="form-label">Reason (optional)</label>
                                                <input type="text" name="reason" maxlength="255" class="form-input">
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
        </div>
        <div class="mt-4">{{ $batches->links() }}</div>
    @endif
@endsection
