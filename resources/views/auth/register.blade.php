@extends('layouts.guest')
@section('title', 'Create an Account')

@section('content')
    <h2 class="text-lg font-bold text-ink-900 mb-1">Create your account</h2>
    <p class="text-sm text-ink-400 mb-6">New accounts start with <strong>Staff</strong> access. An admin can upgrade your role later from User Management.</p>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="form-label" for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus maxlength="150" class="form-input">
            @error('name') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" required class="form-input" placeholder="At least 8 characters">
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
        </div>
        <button type="submit" class="btn-primary w-full">Create Account</button>
    </form>

    <p class="text-center text-sm text-ink-400 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-medium hover:text-brand-700">Sign in</a>
    </p>
@endsection
