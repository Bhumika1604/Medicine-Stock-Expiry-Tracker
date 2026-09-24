@props(['action', 'confirm' => 'Are you sure you want to delete this item?', 'label' => 'Delete'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm }}');" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'text-red-600 hover:text-red-800 text-sm font-medium']) }}>{{ $label }}</button>
</form>
