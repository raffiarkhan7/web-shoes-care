@props(['icon' => 'box', 'title', 'description' => null])

<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/60 px-6 py-14 text-center">
    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
        <x-icon :name="$icon" class="h-7 w-7" />
    </span>
    <h3 class="mt-4 text-base font-semibold text-slate-800">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @endif
    @if (isset($slot) && trim($slot) !== '')
        <div class="mt-5">{{ $slot }}</div>
    @endif
</div>
