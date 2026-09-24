@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-ink-900">Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p class="text-sm text-ink-400 mt-1">Here's what's happening in your inventory today, {{ now()->format('d M Y') }}.</p>
    </div>

    @if (count($alerts))
        <div class="mb-6 space-y-2">
            @foreach ($alerts as $alert)
                <a href="{{ $alert['url'] }}" class="flex items-center justify-between rounded-lg px-4 py-3 text-sm border {{ $alert['type'] === 'danger' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800' }} hover:opacity-90 transition">
                    <span class="flex items-center gap-2 font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        {{ $alert['text'] }}
                    </span>
                    <span class="text-xs font-semibold">View →</span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Medicines" value="{{ $stats['total_medicines'] }}" href="{{ route('medicines.index') }}" tone="brand" icon="M19 7h-3V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2z" />
        <x-stat-card label="Total Batches" value="{{ $stats['total_batches'] }}" href="{{ route('stock.history') }}" icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        <x-stat-card label="Total Stock Quantity" value="{{ number_format($stats['total_stock']) }}" href="{{ route('stock.history') }}" icon="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2z" />
        <x-stat-card label="Categories" value="{{ $stats['categories'] }}" href="{{ route('categories.index') }}" icon="M4 6h16M4 10h16M4 14h10M4 18h6" />
        <x-stat-card label="Low Stock Items" value="{{ $stats['low_stock'] }}" href="{{ route('stock.history') }}?status=low" tone="warning" icon="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-8.25 3.75h.008v.008h-.008v-.008z" />
        <x-stat-card label="Out-of-Stock Items" value="{{ $stats['out_of_stock'] }}" href="{{ route('out-of-stock.index') }}" tone="danger" icon="M6 18L18 6M6 6l12 12" />
        <x-stat-card label="Near Expiry Items" value="{{ $stats['near_expiry'] }}" href="{{ route('expiry-alerts.index', ['tab' => 'near_expiry']) }}" tone="warning" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        <x-stat-card label="Expired Items" value="{{ $stats['expired'] }}" href="{{ route('expiry-alerts.index', ['tab' => 'expired']) }}" tone="danger" icon="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="card lg:col-span-1">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Stock by Category</p>
            <canvas id="categoryChart" height="220"></canvas>
        </div>
        <div class="card lg:col-span-1">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Medicine Expiry Status</p>
            <canvas id="expiryChart" height="220"></canvas>
        </div>
        <div class="card lg:col-span-1">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Stock Status</p>
            <canvas id="stockChart" height="220"></canvas>
        </div>
    </div>

    {{-- Recent activity + quick actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="card lg:col-span-2">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Recent Activity</p>
            @if ($recentActivity->isEmpty())
                <p class="text-sm text-ink-400 py-6 text-center">No recent stock activity yet.</p>
            @else
                <div class="divide-y divide-ink-100">
                    @foreach ($recentActivity as $tx)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-badge :color="$tx->typeBadgeColor()" :label="$tx->transaction_type" />
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-ink-800 truncate">{{ $tx->batch->medicine->name ?? 'Medicine' }} <span class="text-ink-400 font-normal">· Batch {{ $tx->batch->batch_number ?? '-' }}</span></p>
                                    <p class="text-xs text-ink-400">{{ $tx->reason ?? 'Stock change' }} — {{ $tx->previous_quantity }} → {{ $tx->new_quantity }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-ink-400 shrink-0">{{ $tx->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card">
            <p class="font-semibold text-ink-800 mb-3 text-sm">Quick Actions</p>
            <div class="space-y-2">
                @if (auth()->user()->canManageInventory())
                    <a href="{{ route('medicines.create') }}" class="btn-secondary w-full justify-start">+ Add Medicine</a>
                @endif
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('categories.create') }}" class="btn-secondary w-full justify-start">+ Add Category</a>
                @endif
                <a href="{{ route('expiry-alerts.index') }}" class="btn-secondary w-full justify-start">View Expiry Alerts</a>
                @if (auth()->user()->canViewReports())
                    <a href="{{ route('reports.index') }}" class="btn-secondary w-full justify-start">Generate Report</a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.3/chart.umd.min.js"></script>
<script>
    const brand = '#17b377', amber = '#f59e0b', red = '#ef4444', sky = '#0ea5e9', ink = '#8090a3';

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($stockByCategory->pluck('name')) !!},
            datasets: [{
                label: 'Stock Qty',
                data: {!! json_encode($stockByCategory->pluck('total_quantity')) !!},
                backgroundColor: brand,
                borderRadius: 6,
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('expiryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Valid', 'Near Expiry', 'Expired'],
            datasets: [{
                data: [{{ $expiryBreakdown['valid'] }}, {{ $expiryBreakdown['near_expiry'] }}, {{ $expiryBreakdown['expired'] }}],
                backgroundColor: [brand, amber, red],
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('stockChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [{{ $stockBreakdown['in_stock'] }}, {{ $stockBreakdown['low_stock'] }}, {{ $stockBreakdown['out_of_stock'] }}],
                backgroundColor: [brand, amber, red],
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
