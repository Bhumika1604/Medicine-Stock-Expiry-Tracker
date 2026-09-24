@php $user = $user ?? null; @endphp
<div>
    <label class="form-label">Full Name *</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" maxlength="150" required class="form-input">
    @error('name') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label">Email Address *</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="form-input">
    @error('email') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label">Role *</label>
    <select name="role" required class="form-input" @disabled($user && $user->id === auth()->id())>
        @foreach (\App\Models\User::ROLES as $role)
            <option value="{{ $role }}" @selected(old('role', $user->role ?? 'staff') === $role)>{{ ucfirst($role) }}</option>
        @endforeach
    </select>
    @if ($user && $user->id === auth()->id())
        <input type="hidden" name="role" value="{{ $user->role }}">
        <p class="text-xs text-ink-400 mt-1">You cannot change your own role.</p>
    @endif
    @error('role') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label">Password {{ $user ? '(leave blank to keep current)' : '*' }}</label>
    <input type="password" name="password" class="form-input" placeholder="At least 8 characters" {{ $user ? '' : 'required' }}>
    @error('password') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label">Confirm Password</label>
    <input type="password" name="password_confirmation" class="form-input">
</div>
<label class="flex items-center gap-2 text-sm text-ink-600">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500" @disabled($user && $user->id === auth()->id())>
    Active (can log in)
</label>
