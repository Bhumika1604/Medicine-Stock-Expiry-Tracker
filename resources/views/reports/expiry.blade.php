@extends('layouts.app')
@section('title', 'Expiry Report')
@section('page-title', 'Expiry Report')

@section('content')
    @php $reportType = 'expiry'; @endphp
    <div class="mb-5 flex items-center justify-between print:hidden">
        <div>
            <h2 class="text-lg font-bold text-ink-900">Expiry Report</h2>
            <p class="text-sm text-ink-400">Near-expiry and expired batches, with days remaining/expired.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="text-sm text-ink-500 hover:text-brand-600">← Back to Reports</a>
    </div>

    @include('reports._filters')
    @include('reports._table')
@endsection
