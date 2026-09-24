@extends('layouts.app')
@section('title', 'Edit Medicine')
@section('page-title', 'Edit Medicine')

@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-1">Edit Medicine</h2>
            <p class="text-sm text-ink-400 mb-6">Update the medicine's details below.</p>

            <form method="POST" action="{{ route('medicines.update', $medicine) }}" class="space-y-4">
                @csrf
                @method('PUT')
                @include('medicines._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Update Medicine</button>
                    <a href="{{ route('medicines.show', $medicine) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
