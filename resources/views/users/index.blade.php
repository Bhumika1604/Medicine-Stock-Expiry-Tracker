@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-ink-900">User Management</h2>
            <p class="text-sm text-ink-400">{{ $users->total() }} user account(s)</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">+ Add User</a>
    </div>

    <form method="GET" class="card mb-5 grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
        <div class="sm:col-span-2">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="form-input">
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-input">
                <option value="">All</option>
                @foreach (\App\Models\User::ROLES as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-3 flex gap-2">
            <button class="btn-primary">Search</button>
            @if (request()->anyFilled(['search','role']))
                <a href="{{ route('users.index') }}" class="btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="card"><x-empty-state title="No users found" subtitle="Try a different search, or add a new user." action-label="+ Add User" :action-url="route('users.create')" /></div>
    @else
        <div class="card p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($users as $user)
                        <tr class="table-row-hover">
                            <td class="px-4 py-3 font-medium text-ink-800">{{ $user->name }} @if($user->id === auth()->id()) <span class="text-xs text-ink-400">(you)</span> @endif</td>
                            <td class="px-4 py-3 text-ink-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <x-badge :color="$user->isAdmin() ? 'blue' : ($user->isPharmacist() ? 'green' : 'orange')" :label="$user->roleLabel()" />
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :color="$user->is_active ? 'green' : 'red'" :label="$user->is_active ? 'Active' : 'Inactive'" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('users.edit', $user) }}" class="text-ink-500 hover:text-brand-600 text-sm font-medium">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <x-delete-form :action="route('users.destroy', $user)" confirm="Are you sure you want to delete this user?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    @endif
@endsection
