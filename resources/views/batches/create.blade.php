@extends('layouts.app')
@section('title', 'Add Batch')
@section('page-title', 'Add Batch')

@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-1">Add Batch — {{ $medicine->name }}</h2>
            <p class="text-sm text-ink-400 mb-6">Track a new batch with its own expiry, quantity and pricing.</p>

            <form method="POST" action="{{ route('batches.store', $medicine) }}" class="space-y-4">
                @csrf
                @include('batches._form', ['defaultMinStock' => $defaultMinStock])
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Save Batch</button>
                    <a href="{{ route('medicines.show', $medicine) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
