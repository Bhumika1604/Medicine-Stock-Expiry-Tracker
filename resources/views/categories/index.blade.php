@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-ink-900">Categories</h2>
            <p class="text-sm text-ink-400">{{ $categories->total() }} categories</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-primary">+ Add Category</a>
    </div>

    <form method="GET" class="card mb-5 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="form-input">
        <button class="btn-primary shrink-0">Search</button>
        @if (request('search'))
            <a href="{{ route('categories.index') }}" class="btn-secondary shrink-0">Reset</a>
        @endif
    </form>

    @if ($categories->isEmpty())
        <div class="card">
            <x-empty-state title="No categories found" subtitle="Create categories like Tablets, Syrups, or Injections to organize your medicines." action-label="+ Add Category" :action-url="route('categories.create')" />
        </div>
    @else
        <div class="card p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-right">Medicines</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($categories as $category)
                        <tr class="table-row-hover">
                            <td class="px-4 py-3 font-medium text-ink-800">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-ink-500">{{ \Illuminate\Support\Str::limit($category->description, 60) ?: '-' }}</td>
                            <td class="px-4 py-3 text-right">{{ $category->medicines_count }}</td>
                            <td class="px-4 py-3">
                                <x-badge :color="$category->status ? 'green' : 'red'" :label="$category->status ? 'Active' : 'Inactive'" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-ink-500 hover:text-brand-600 text-sm font-medium">Edit</a>
                                    <x-delete-form :action="route('categories.destroy', $category)" confirm="Are you sure you want to delete this category?" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    @endif
@endsection
