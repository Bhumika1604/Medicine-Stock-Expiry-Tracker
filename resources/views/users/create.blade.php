@extends('layouts.app')
@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
    <div class="max-w-lg">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-6">Add User</h2>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf
                @include('users._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Create User</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
