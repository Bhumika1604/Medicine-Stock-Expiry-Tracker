@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <h2 class="text-lg font-bold text-ink-900 mb-1">Welcome back</h2>
    <p class="text-sm text-ink-400 mb-6">Sign in to manage your medicine inventory.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf
        <div>
            <label class="form-label" for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-input" placeholder="admin@meditrack.test">
        </div>
        <div>
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" required class="form-input" placeholder="••••••••">
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-ink-600">
                <input type="checkbox" name="remember" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                Remember me
            </label>
        </div>
        <button type="submit" class="btn-primary w-full">Login</button>
    </form>

    <div class="mt-6 pt-5 border-t border-ink-100 text-xs text-ink-400">
        <p class="font-semibold text-ink-500 mb-1">Demo credentials</p>
        <p>Admin: <span class="font-mono">admin@meditrack.test</span> / <span class="font-mono">password</span></p>
        <p>Pharmacist: <span class="font-mono">pharmacist@meditrack.test</span> / <span class="font-mono">password</span></p>
        <p>Staff: <span class="font-mono">staff@meditrack.test</span> / <span class="font-mono">password</span></p>
    </div>

    <p class="text-center text-sm text-ink-400 mt-6">
        New here? <a href="{{ route('register') }}" class="text-brand-600 font-medium hover:text-brand-700">Create an account</a>
    </p>
@endsection
