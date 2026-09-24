@props(['color' => 'green', 'label'])
<span {{ $attributes->merge(['class' => "badge-{$color}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ $label ?? $slot }}
</span>
