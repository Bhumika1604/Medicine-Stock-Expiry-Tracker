@extends('layouts.app')
@section('title', 'Inventory Report')
@section('page-title', 'Inventory Report')

@section('content')
    @php $reportType = 'inventory'; @endphp
    <div class="mb-5 flex items-center justify-between print:hidden">
        <div>
            <h2 class="text-lg font-bold text-ink-900">Inventory Report</h2>
            <p class="text-sm text-ink-400">Full batch-wise inventory listing.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="text-sm text-ink-500 hover:text-brand-600">← Back to Reports</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5 print:hidden">
        <div class="card"><p class="text-xl font-bold text-ink-900">{{ $summary['total_medicines'] }}</p><p class="text-xs text-ink-400">Total Medicines</p></div>
        <div class="card"><p class="text-xl font-bold text-ink-900">{{ $summary['total_batches'] }}</p><p class="text-xs text-ink-400">Total Batches</p></div>
        <div class="card"><p class="text-xl font-bold text-ink-900">{{ number_format($summary['total_quantity']) }}</p><p class="text-xs text-ink-400">Total Quantity</p></div>
        <div class="card"><p class="text-xl font-bold text-ink-900">{{ $summary['categories'] }}</p><p class="text-xs text-ink-400">Categories</p></div>
    </div>

    @include('reports._filters')
    @include('reports._table')
@endsection
