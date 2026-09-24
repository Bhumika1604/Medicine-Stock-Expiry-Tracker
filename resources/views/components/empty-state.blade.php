@props(['title' => 'Nothing here yet', 'subtitle' => null, 'actionLabel' => null, 'actionUrl' => null])
<div class="flex flex-col items-center justify-center text-center py-16 px-4">
    <div class="w-16 h-16 rounded-full bg-ink-100 flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4l-2 3h-4l-2-3H4" /></svg>
    </div>
    <p class="text-ink-700 font-semibold">{{ $title }}</p>
    @if ($subtitle)
        <p class="text-ink-400 text-sm mt-1 max-w-sm">{{ $subtitle }}</p>
    @endif
    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn-primary mt-5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
