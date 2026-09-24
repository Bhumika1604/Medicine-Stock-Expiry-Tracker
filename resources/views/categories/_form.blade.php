@php $category = $category ?? null; @endphp
<div>
    <label class="form-label">Category Name *</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" maxlength="100" required class="form-input">
    @error('name') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" maxlength="1000" class="form-input">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>
<label class="flex items-center gap-2 text-sm text-ink-600">
    <input type="checkbox" name="status" value="1" @checked(old('status', $category->status ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500">
    Active
</label>
