@extends('layouts.app')
@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
    <div class="max-w-lg">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-6">Add Category</h2>
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                @include('categories._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Save Category</button>
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
