@props([
    'icon' => 'fa-folder-open',
    'title' => 'Tidak Ada Data',
    'description' => null,
    'actionUrl' => null,
    'actionLabel' => null,
])

<div class="py-10 px-4 text-center">
    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
    <h4 class="text-sm font-bold text-slate-800">{{ $title }}</h4>
    @if($description)
        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @if($actionUrl && $actionLabel)
        <div class="mt-4">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus"></i>
                <span>{{ $actionLabel }}</span>
            </a>
        </div>
    @endif
</div>