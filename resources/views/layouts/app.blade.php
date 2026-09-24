<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileOpen: false }" class="min-h-screen flex">

    {{-- Sidebar (desktop) --}}
    <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-ink-900 text-white px-4 py-6">
        @include('layouts.partials.sidebar-content')
    </aside>

    {{-- Sidebar (mobile drawer) --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden fixed inset-0 z-40 flex">
        <div class="fixed inset-0 bg-black/40" @click="mobileOpen = false"></div>
        <aside class="relative flex flex-col w-72 bg-ink-900 text-white px-4 py-6 z-50">
            @include('layouts.partials.sidebar-content')
        </aside>
    </div>

    <div class="flex-1 flex flex-col min-w-0">
        {{-- Top navbar --}}
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-ink-100">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3.5">
                <div class="flex items-center gap-3">
                    <button @click="mobileOpen = true" class="lg:hidden text-ink-600 hover:text-ink-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-ink-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('expiry-alerts.index') }}" class="relative text-ink-500 hover:text-brand-600" title="Expiry Alerts">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:opacity-80" title="My Profile">
                        <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-ink-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-ink-500 hover:text-red-600 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 sm:px-6 py-6">
            <x-flash-messages />
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
