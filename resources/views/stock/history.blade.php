@extends('layouts.app')
@section('title', 'Stock History')
@section('page-title', 'Stock History')

@section('content')
    <div class="mb-5">
        <h2 class="text-lg font-bold text-ink-900">Stock Transaction History</h2>
        <p class="text-sm text-ink-400">{{ $transactions->total() }} transaction(s) recorded.</p>
    </div>

    <form method="GET" class="card mb-5 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        <div>
            <label class="form-label">Medicine</label>
            <select name="medicine_id" class="form-input">
                <option value="">All</option>
                @foreach ($medicines as $m)
                    <option value="{{ $m->id }}" @selected(request('medicine_id') == $m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Type</label>
            <select name="type" class="form-input">
                <option value="">All</option>
                <option value="IN" @selected(request('type')=='IN')>IN</option>
                <option value="OUT" @selected(request('type')=='OUT')>OUT</option>
                <option value="ADJUSTMENT" @selected(request('type')=='ADJUSTMENT')>ADJUSTMENT</option>
            </select>
        </div>
        <div>
            <label class="form-label">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-input">
        </div>
        <div class="flex gap-2">
            <button class="btn-primary w-full">Filter</button>
            @if (request()->anyFilled(['medicine_id','type','from','to']))
                <a href="{{ route('stock.history') }}" class="btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    @if ($transactions->isEmpty())
        <div class="card"><x-empty-state title="No stock history yet" subtitle="Stock transactions will appear here as batches are added or updated." /></div>
    @else
        <div class="card p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Medicine</th>
                        <th class="px-4 py-3 text-left">Batch</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-right">Qty Change</th>
                        <th class="px-4 py-3 text-right">Previous</th>
                        <th class="px-4 py-3 text-right">New</th>
                        <th class="px-4 py-3 text-left">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($transactions as $tx)
                        <tr class="table-row-hover">
                            <td class="px-4 py-3 text-ink-600 whitespace-nowrap">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 font-medium text-ink-800">{{ $tx->batch->medicine->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $tx->batch->batch_number ?? '-' }}</td>
                            <td class="px-4 py-3"><x-badge :color="$tx->typeBadgeColor()" :label="$tx->transaction_type" /></td>
                            <td class="px-4 py-3 text-right font-medium">{{ $tx->quantity }}</td>
                            <td class="px-4 py-3 text-right text-ink-500">{{ $tx->previous_quantity }}</td>
                            <td class="px-4 py-3 text-right text-ink-800 font-medium">{{ $tx->new_quantity }}</td>
                            <td class="px-4 py-3 text-ink-500">{{ $tx->reason ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $transactions->links() }}</div>
    @endif
@endsection
