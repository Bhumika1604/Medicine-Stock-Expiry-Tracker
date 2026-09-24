@extends('layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
    <div class="max-w-lg">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-6">Edit Category</h2>
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
                @csrf
                @method('PUT')
                @include('categories._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Update Category</button>
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
