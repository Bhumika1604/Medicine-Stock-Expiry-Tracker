@extends('layouts.app')
@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="max-w-xl">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-ink-900">Settings</h2>
            <p class="text-sm text-ink-400">Configure organization details and inventory defaults.</p>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label">Pharmacy / Organization Name *</label>
                    <input type="text" name="pharmacy_name" value="{{ old('pharmacy_name', $settings['pharmacy_name']) }}" required maxlength="150" class="form-input">
                    @error('pharmacy_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Near-Expiry Threshold (days) *</label>
                    <input type="number" name="near_expiry_days" value="{{ old('near_expiry_days', $settings['near_expiry_days']) }}" min="1" max="365" required class="form-input">
                    <p class="text-xs text-ink-400 mt-1">Batches expiring within this many days are flagged as "Near Expiry". Default: 30.</p>
                    @error('near_expiry_days') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Default Minimum Stock Level *</label>
                    <input type="number" name="default_min_stock" value="{{ old('default_min_stock', $settings['default_min_stock']) }}" min="0" required class="form-input">
                    <p class="text-xs text-ink-400 mt-1">Pre-filled as the minimum stock level when adding a new batch.</p>
                    @error('default_min_stock') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Currency Symbol *</label>
                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" maxlength="5" required class="form-input">
                    @error('currency_symbol') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pt-2">
                    <button class="btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection
