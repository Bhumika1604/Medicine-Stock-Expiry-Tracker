@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-lg">
        <div class="card">
            <h2 class="text-lg font-bold text-ink-900 mb-6">Edit User — {{ $user->name }}</h2>
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')
                @include('users._form')
                <div class="flex gap-3 pt-2">
                    <button class="btn-primary">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
