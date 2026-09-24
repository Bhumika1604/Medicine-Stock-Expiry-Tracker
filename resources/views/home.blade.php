<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }} — Medicine Stock &amp; Expiry Tracker</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-ink-800">

    {{-- Top nav --}}
    <header class="border-b border-ink-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-brand-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V5a2 2 0 00-2-2H10a2 2 0 00-2 2v2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2zM10 5h4v2h-4V5zm2 6v6m-3-3h6" /></svg>
                </div>
                <span class="font-bold text-ink-900">{{ $appSettings['pharmacy_name'] ?? 'MediTrack' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24 text-center">
        <span class="badge-green mx-auto mb-5">Built for pharmacies &amp; medical stores</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-ink-900 leading-tight max-w-3xl mx-auto">
            Never lose track of expiring stock again
        </h1>
        <p class="text-ink-500 text-base sm:text-lg mt-5 max-w-xl mx-auto">
            {{ $appSettings['pharmacy_name'] ?? 'MediTrack' }} tracks every batch, quantity and expiry date in one
            place — with automatic low-stock and near-expiry alerts, so nothing slips through the cracks.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-8">
            <a href="{{ route('register') }}" class="btn-primary !px-6 !py-3 text-base">Create a free account</a>
            <a href="{{ route('login') }}" class="btn-secondary !px-6 !py-3 text-base">Sign in</a>
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-ink-50 border-y border-ink-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="card">
                    <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="font-semibold text-ink-900">Automatic Expiry Alerts</p>
                    <p class="text-sm text-ink-500 mt-1">Every batch is checked against a configurable near-expiry window — no manual spreadsheets.</p>
                </div>
                <div class="card">
                    <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <p class="font-semibold text-ink-900">Batch-Wise Stock Tracking</p>
                    <p class="text-sm text-ink-500 mt-1">Track quantity, supplier and pricing per batch, with a full audit trail of every stock movement.</p>
                </div>
                <div class="card">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
                    </div>
                    <p class="font-semibold text-ink-900">Role-Based Access</p>
                    <p class="text-sm text-ink-500 mt-1">Admins, Pharmacists and Staff each see exactly what they need — nothing more.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="max-w-6xl mx-auto px-4 sm:px-6 py-8 text-center text-xs text-ink-400">
        &copy; {{ date('Y') }} {{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }}. Internal inventory system.
    </footer>
</body>
</html>
