@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-ink-900">Reports</h2>
        <p class="text-sm text-ink-400">Generate and export inventory, expiry, and stock reports.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <a href="{{ route('reports.inventory') }}" class="card hover:border-brand-300">
            <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2z" /></svg>
            </div>
            <p class="font-semibold text-ink-900">Inventory Report</p>
            <p class="text-sm text-ink-400 mt-1">Total medicines, batches, quantity and category breakdown.</p>
        </a>
        <a href="{{ route('reports.expiry') }}" class="card hover:border-brand-300">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="font-semibold text-ink-900">Expiry Report</p>
            <p class="text-sm text-ink-400 mt-1">Near-expiry and expired batches with days remaining.</p>
        </a>
        <a href="{{ route('reports.stock') }}" class="card hover:border-brand-300">
            <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <p class="font-semibold text-ink-900">Stock Report</p>
            <p class="text-sm text-ink-400 mt-1">In-stock, low-stock and out-of-stock batch breakdown.</p>
        </a>
    </div>
@endsection
