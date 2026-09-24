<div class="flex items-center gap-2.5 px-2 mb-8">
    <div class="w-9 h-9 rounded-lg bg-brand-500 flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V5a2 2 0 00-2-2H10a2 2 0 00-2 2v2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2zM10 5h4v2h-4V5zm2 6v6m-3-3h6" /></svg>
    </div>
    <div>
        <p class="text-sm font-bold text-white leading-tight">{{ $appSettings['pharmacy_name'] ?? 'MediTrack' }}</p>
        <p class="text-[11px] text-ink-400">Stock &amp; Expiry Tracker</p>
    </div>
</div>

@auth
<div class="flex items-center gap-2.5 px-2 mb-4 pb-4 border-b border-white/10">
    <div class="w-8 h-8 rounded-full bg-brand-500/20 text-brand-300 flex items-center justify-center text-xs font-semibold shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
    <div class="min-w-0">
        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
        <p class="text-[11px] text-ink-400">{{ auth()->user()->roleLabel() }}</p>
    </div>
</div>
@endauth

<nav class="flex-1 space-y-1">
    @php
        $user = auth()->user();
        $links = [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'medicines.index', 'label' => 'Medicines', 'icon' => 'M19 7h-3V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2z'],
            ['route' => 'categories.index', 'label' => 'Categories', 'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h6', 'roles' => ['admin']],
            ['route' => 'stock.history', 'label' => 'Batches / Stock', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['route' => 'expiry-alerts.index', 'label' => 'Expiry Alerts', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route' => 'out-of-stock.index', 'label' => 'Out-of-Stock', 'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-8.25 3.75h.008v.008h-.008v-.008z'],
            ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2z', 'roles' => ['admin', 'pharmacist']],
            ['route' => 'users.index', 'label' => 'User Management', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4', 'roles' => ['admin']],
            ['route' => 'settings.edit', 'label' => 'Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'roles' => ['admin']],
            ['route' => 'profile.edit', 'label' => 'My Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp

    @foreach ($links as $link)
        @continue(isset($link['roles']) && ! $user->hasAnyRole($link['roles']))
        <a href="{{ route($link['route']) }}" class="sidebar-link {{ request()->routeIs(explode('.', $link['route'])[0].'*') ? 'active' : '' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" /></svg>
            <span>{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>

<form method="POST" action="{{ route('logout') }}" class="mt-4">
    @csrf
    <button class="sidebar-link w-full text-left">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
        <span>Logout</span>
    </button>
</form>
