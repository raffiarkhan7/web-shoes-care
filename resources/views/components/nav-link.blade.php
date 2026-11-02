@props(['href', 'active' => false, 'icon' => 'dashboard'])

<a href="{{ $href }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition {{ $active ? 'bg-white/15 text-white shadow-sm' : 'text-teal-100/75 hover:bg-white/10 hover:text-white' }}">
    <x-icon :name="$icon" class="h-5 w-5 shrink-0 {{ $active ? 'text-white' : 'text-teal-200/70 group-hover:text-white' }}" />
    <span>{{ $slot }}</span>
</a>
