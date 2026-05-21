@props(['status'])

@php
    $styles = match ($status) {
        'replied' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'waiting_reply' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        default => 'bg-slate-50 text-slate-600 ring-slate-500/20',
    };
    $label = match ($status) {
        'replied' => 'تم الرد',
        'waiting_reply' => 'في انتظار الرد',
        default => $status,
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {$styles}"]) }}>
    {{ $label }}
</span>
