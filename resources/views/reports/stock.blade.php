@extends('layouts.app')
@section('title', 'Stock Report')
@section('page-title', 'Stock Report')

@section('content')
    @php $reportType = 'stock'; @endphp
    <div class="mb-5 flex items-center justify-between print:hidden">
        <div>
            <h2 class="text-lg font-bold text-ink-900">Stock Report</h2>
            <p class="text-sm text-ink-400">In-stock, low-stock and out-of-stock batch breakdown.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="text-sm text-ink-500 hover:text-brand-600">← Back to Reports</a>
    </div>

    @include('reports._filters')
    @include('reports._table')
@endsection
