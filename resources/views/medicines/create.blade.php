@extends('layouts.app')
@section('title', 'Add Medicine')
@section('page-title', 'Add Medicine')

@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-1">Medicine Information</h2>
            <p class="text-sm text-ink-400 mb-6">Fields marked * are required.</p>

            <form method="POST" action="{{ route('medicines.store') }}" class="space-y-4">
                @csrf
                @include('medicines._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Save Medicine</button>
                    <a href="{{ route('medicines.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
