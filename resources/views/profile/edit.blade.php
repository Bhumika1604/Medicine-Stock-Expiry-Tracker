@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="max-w-xl space-y-5">
        <div>
            <h2 class="text-lg font-bold text-ink-900">My Profile</h2>
            <p class="text-sm text-ink-400">Update your account details. Role: <span class="font-semibold text-ink-700">{{ $user->roleLabel() }}</span></p>
        </div>

        <div class="card">
            <p class="font-semibold text-ink-800 mb-4 text-sm">Account Information</p>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="150" required class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button class="btn-primary">Save Changes</button>
            </form>
        </div>

        <div class="card">
            <p class="font-semibold text-ink-800 mb-4 text-sm">Change Password</p>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" required class="form-input">
                    @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" required class="form-input" placeholder="At least 8 characters">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
                <button class="btn-primary">Update Password</button>
            </form>
        </div>
    </div>
@endsection
