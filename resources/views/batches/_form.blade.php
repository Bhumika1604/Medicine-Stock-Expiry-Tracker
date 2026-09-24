@php $batch = $batch ?? null; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Batch Number *</label>
        <input type="text" name="batch_number" value="{{ old('batch_number', $batch->batch_number ?? '') }}" maxlength="50" required class="form-input">
        @error('batch_number') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" value="{{ old('supplier_name', $batch->supplier_name ?? '') }}" maxlength="150" class="form-input">
        @error('supplier_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Manufacturing Date</label>
        <input type="date" name="manufacturing_date" value="{{ old('manufacturing_date', optional($batch->manufacturing_date ?? null)->format('Y-m-d')) }}" class="form-input">
        @error('manufacturing_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Expiry Date *</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($batch->expiry_date ?? null)->format('Y-m-d')) }}" required class="form-input">
        @error('expiry_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Quantity *</label>
        <input type="number" name="quantity" min="0" value="{{ old('quantity', $batch->quantity ?? 0) }}" required class="form-input">
        @error('quantity') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Minimum Stock Level *</label>
        <input type="number" name="minimum_stock_level" min="0" value="{{ old('minimum_stock_level', $batch->minimum_stock_level ?? ($defaultMinStock ?? 10)) }}" required class="form-input">
        @error('minimum_stock_level') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Purchase Price ({{ $appSettings['currency_symbol'] ?? '₹' }})</label>
        <input type="number" step="0.01" name="purchase_price" min="0" value="{{ old('purchase_price', $batch->purchase_price ?? '') }}" class="form-input">
        @error('purchase_price') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Selling Price ({{ $appSettings['currency_symbol'] ?? '₹' }})</label>
        <input type="number" step="0.01" name="selling_price" min="0" value="{{ old('selling_price', $batch->selling_price ?? '') }}" class="form-input">
        @error('selling_price') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>
