@extends('layouts.app')
@section('title', 'Expiry Alerts')
@section('page-title', 'Expiry Alerts')

@section('content')
    <div class="mb-5">
        <h2 class="text-lg font-bold text-ink-900">Expiry Alerts</h2>
        <p class="text-sm text-ink-400">Batches expiring within {{ $nearExpiryDays }} days are flagged as near expiry.</p>
    </div>

    <div class="flex gap-2 mb-5 border-b border-ink-200">
        @foreach (['near_expiry' => 'Near Expiry', 'expired' => 'Expired', 'valid' => 'Valid'] as $key => $label)
            <a href="{{ route('expiry-alerts.index', ['tab' => $key]) }}"
               class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors {{ $tab === $key ? 'border-brand-600 text-brand-700' : 'border-transparent text-ink-400 hover:text-ink-600' }}">
                {{ $label }} <span class="ml-1 text-xs">({{ $counts[$key] }})</span>
            </a>
        @endforeach
    </div>

    <form method="GET" class="card mb-5 flex flex-wrap gap-3 items-end">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div>
            <label class="form-label">Expiry From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Expiry To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-input">
        </div>
        <button class="btn-primary">Filter</button>
        @if (request()->anyFilled(['from','to']))
            <a href="{{ route('expiry-alerts.index', ['tab' => $tab]) }}" class="btn-secondary">Reset</a>
        @endif
    </form>

    @if ($batches->isEmpty())
        <div class="card"><x-empty-state title="No batches in this category" subtitle="Nothing to show for the selected filter right now." /></div>
    @else
        <div class="card p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Medicine</th>
                        <th class="px-4 py-3 text-left">Batch No.</th>
                        <th class="px-4 py-3 text-left">Expiry Date</th>
                        <th class="px-4 py-3 text-left">{{ $tab === 'expired' ? 'Expired By' : 'Days Remaining' }}</th>
                        <th class="px-4 py-3 text-right">Quantity</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($batches as $batch)
                        @php $days = $batch->getDaysRemaining(); @endphp
                        <tr class="table-row-hover">
                            <td class="px-4 py-3 font-medium text-ink-800"><a href="{{ route('medicines.show', $batch->medicine) }}" class="hover:text-brand-600">{{ $batch->medicine->name }}</a></td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-3 text-ink-600">{{ $batch->expiry_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-ink-600">
                                @if ($days < 0)
                                    {{ abs($days) }} day(s) ago
                                @else
                                    {{ $days }} day(s) remaining
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-medium">{{ $batch->quantity }}</td>
                            <td class="px-4 py-3"><x-badge :color="$batch->expiryBadgeColor()" :label="$batch->getExpiryStatus()" /></td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('medicines.show', $batch->medicine) }}" class="text-brand-600 hover:text-brand-800 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $batches->links() }}</div>
    @endif
@endsection
