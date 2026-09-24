@extends('layouts.app')
@section('title', 'Edit Batch')
@section('page-title', 'Edit Batch')

@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-1">Edit Batch — {{ $batch->batch_number }}</h2>
            <p class="text-sm text-ink-400 mb-6">{{ $medicine->name }}</p>

            <form method="POST" action="{{ route('batches.update', [$medicine, $batch]) }}" class="space-y-4">
                @csrf
                @method('PUT')
                @include('batches._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Update Batch</button>
                    <a href="{{ route('medicines.show', $medicine) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
