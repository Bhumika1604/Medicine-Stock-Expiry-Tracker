@extends('layouts.app')
@section('title', 'Access Denied')
@section('page-title', 'Access Denied')

@section('content')
    <div class="flex flex-col items-center justify-center text-center py-20">
        <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        </div>
        <p class="text-ink-900 font-semibold text-lg">Access Denied</p>
        <p class="text-ink-400 text-sm mt-1 max-w-sm">{{ $exception->getMessage() ?: "You don't have permission to view this page." }}</p>
        <a href="{{ route('dashboard') }}" class="btn-primary mt-6">← Back to Dashboard</a>
    </div>
@endsection
