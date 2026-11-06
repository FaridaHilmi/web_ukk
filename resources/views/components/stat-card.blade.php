@props([
    'label' => 'Statistik',
    'value' => 0,
    'icon' => 'fa-chart-simple',
    'color' => 'blue',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
])

@php
    $colorMap = [
        'blue'    => 'bg-blue-50 text-blue-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'indigo'  => 'bg-indigo-50 text-indigo-600',
        'violet'  => 'bg-purple-50 text-purple-600',
        'purple'  => 'bg-purple-50 text-purple-600',
        'amber'   => 'bg-amber-50 text-amber-600',
        'rose'    => 'bg-rose-50 text-rose-600',
    ];
    $iconClass = $colorMap[$color] ?? 'bg-blue-50 text-blue-600';
@endphp

<div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ $label }}</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $value }}</h3>
        @if($hint)
            <p class="text-[11px] text-slate-400 font-medium mt-1">{{ $hint }}</p>
        @endif
    </div>
    <div class="w-12 h-12 rounded-xl {{ $iconClass }} flex items-center justify-center text-lg shrink-0">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
</div>