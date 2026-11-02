@props(['label', 'value', 'icon' => 'dashboard', 'tone' => 'teal', 'hint' => null])

@php
    $tones = [
        'teal' => 'bg-teal-50 text-teal-700 ring-teal-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'sky' => 'bg-sky-50 text-sky-700 ring-sky-100',
        'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'slate' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
@endphp

<div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
            @endif
        </div>
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 {{ $tones[$tone] ?? $tones['teal'] }}">
            <x-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</div>
