@props([
    'label',
    'value',
    'accent' => 'blue',
])

@php
    $accentClasses = match ($accent) {
        'violet' => 'bg-violet-50 text-violet-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'rose' => 'bg-rose-50 text-rose-600',
        default => 'bg-blue-50 text-blue-600',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-100 bg-white p-5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-800">{{ $value }}</p>
        </div>
        <div @class(['flex h-11 w-11 items-center justify-center rounded-xl', $accentClasses])>
            {{ $icon }}
        </div>
    </div>
</div>
