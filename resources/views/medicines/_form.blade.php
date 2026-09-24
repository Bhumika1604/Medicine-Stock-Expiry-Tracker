@php $medicine = $medicine ?? null; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Medicine Name *</label>
        <input type="text" name="name" value="{{ old('name', $medicine->name ?? '') }}" maxlength="150" required class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Generic Name</label>
        <input type="text" name="generic_name" value="{{ old('generic_name', $medicine->generic_name ?? '') }}" maxlength="150" class="form-input">
        @error('generic_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Category *</label>
        <select name="category_id" required class="form-input">
            <option value="">Select category</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $medicine->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Manufacturer *</label>
        <input type="text" name="manufacturer" value="{{ old('manufacturer', $medicine->manufacturer ?? '') }}" maxlength="150" required class="form-input">
        @error('manufacturer') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Dosage Form *</label>
        <select name="dosage_form" required class="form-input">
            <option value="">Select dosage form</option>
            @foreach (\App\Models\Medicine::DOSAGE_FORMS as $form)
                <option value="{{ $form }}" @selected(old('dosage_form', $medicine->dosage_form ?? '') == $form)>{{ $form }}</option>
            @endforeach
        </select>
        @error('dosage_form') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Strength</label>
        <input type="text" name="strength" value="{{ old('strength', $medicine->strength ?? '') }}" maxlength="50" placeholder="e.g. 500mg" class="form-input">
        @error('strength') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>
<div>
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" maxlength="2000" class="form-input">{{ old('description', $medicine->description ?? '') }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>
