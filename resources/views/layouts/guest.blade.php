<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') · {{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-ink-900 via-ink-900 to-brand-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-brand-500 flex items-center justify-center shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V5a2 2 0 00-2-2H10a2 2 0 00-2 2v2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2zM10 5h4v2h-4V5zm2 6v6m-3-3h6" /></svg>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }}</h1>
            <p class="text-ink-300 text-sm mt-1">Medicine Stock &amp; Expiry Tracker</p>
        </div>
        <div class="bg-white rounded-xl2 shadow-2xl p-8">
            @yield('content')
        </div>
        <p class="text-center text-ink-400 text-xs mt-6">&copy; {{ date('Y') }} {{ $appSettings['pharmacy_name'] ?? 'MediTrack Pharmacy' }}. Internal inventory system.</p>
    </div>
</body>
</html>
