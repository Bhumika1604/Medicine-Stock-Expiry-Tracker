@props(['label', 'value', 'icon' => 'M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2z', 'href' => null, 'tone' => 'default'])
@php
    $toneClasses = [
        'default' => 'bg-ink-50 text-ink-600',
        'warning' => 'bg-amber-50 text-amber-600',
        'danger' => 'bg-red-50 text-red-600',
        'brand' => 'bg-brand-50 text-brand-600',
    ][$tone] ?? 'bg-ink-50 text-ink-600';
@endphp
<a href="{{ $href ?? '#' }}" class="card flex items-center gap-4 {{ $href ? 'cursor-pointer' : 'cursor-default' }}">
    <div class="w-11 h-11 rounded-xl {{ $toneClasses }} flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" /></svg>
    </div>
    <div class="min-w-0">
        <p class="text-2xl font-bold text-ink-900 leading-tight">{{ $value }}</p>
        <p class="text-xs font-medium text-ink-400 truncate">{{ $label }}</p>
    </div>
</a>
