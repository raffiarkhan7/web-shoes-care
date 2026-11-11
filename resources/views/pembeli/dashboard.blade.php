@extends('layouts.app')

@section('title', 'Dashboard Pembeli')
@section('heading', 'Halo, {{ auth()->user()->name }}')
@section('subheading', 'Sepatu bersih, antar jemput tanpa ribet.')

@section('actions')
    <a href="{{ route('pembeli.orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
        <x-icon name="plus" class="h-4 w-4" /> Pesan layanan
    </a>
@endsection

@section('content')
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-900 via-blue-800 to-emerald-800 p-6 text-white shadow-lg sm:p-8">
        <div class="absolute -right-10 -top-16 h-56 w-56 rounded-full bg-amber-400/20 blur-2xl"></div>
        <div class="absolute -bottom-20 right-24 h-48 w-48 rounded-full bg-emerald-400/20 blur-2xl"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-amber-200 ring-1 ring-white/15">
                    <x-icon name="truck" class="h-3.5 w-3.5" /> Antar jemput gratis
                </span>
                <h2 class="mt-3 text-2xl font-bold leading-tight sm:text-3xl">Sepatu kotor? Kami jemput dan antar kembali bersih.</h2>
                <p class="mt-2 text-sm text-blue-100/80">Pilih layanan, tentukan jadwal jemput, lalu duduk santai. Kami urus sisanya.</p>
                <a href="{{ route('pembeli.orders.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-sm font-bold text-blue-950 shadow-sm transition hover:bg-amber-300">
                    Pesan sekarang <x-icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>
            <div class="grid grid-cols-3 gap-3 lg:w-80">
                <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/15">
                    <p class="text-xl font-bold text-amber-300">{{ $stats['active'] }}</p>
                    <p class="mt-0.5 text-[11px] text-blue-100/70">Pesanan aktif</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/15">
                    <p class="text-xl font-bold text-amber-300">{{ $stats['completed'] }}</p>
                    <p class="mt-0.5 text-[11px] text-blue-100/70">Selesai</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/15">
                    <p class="text-lg font-bold text-amber-300">Rp{{ number_format($stats['spent'] / 1000, 0, ',', '.') }}k</p>
                    <p class="mt-0.5 text-[11px] text-blue-100/70">Total belanja</p>
                </div>
            </div>
        </div>
    </div>

    @if ($activeOrders->isNotEmpty())
        <div class="mt-6">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Pesanan aktif</h2>
                <a href="{{ route('pembeli.orders.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">Lihat semua</a>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach ($activeOrders as $order)
                    <a href="{{ route('pembeli.orders.show', $order) }}" class="group rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $order->invoice_number }}</p>
                                <h3 class="mt-1 text-base font-bold text-slate-900 group-hover:text-blue-700">{{ $order->service?->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $order->quantity }} pasang · {{ $order->penjual?->shop_name ?: $order->penjual?->name }}</p>
                            </div>
                            <x-status-badge :order="$order" />
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3.5">
                            <span class="flex items-center gap-1.5 text-xs text-slate-500">
                                <x-icon name="calendar" class="h-3.5 w-3.5" /> {{ $order->pickup_date->format('d M Y') }} · {{ $order->pickup_time }}
                            </span>
                            <span class="text-sm font-bold text-slate-900">{{ $order->formattedTotal() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <div class="mb-3 flex items-end justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Katalog layanan</h2>
                <p class="text-xs text-slate-500">Pilih perawatan terbaik untuk sepatu kesayanganmu.</p>
            </div>
            <a href="{{ route('pembeli.orders.create') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">Lihat semua</a>
        </div>

        @if ($services->isEmpty())
            <x-empty-state icon="services" title="Belum ada layanan tersedia" description="Penjual belum menambahkan layanan. Coba cek kembali nanti." />
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($services as $service)
                    <div class="group flex flex-col rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-emerald-500 text-white shadow-sm">
                                <x-icon name="shoe" class="h-5 w-5" />
                            </span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-500">{{ $service->categoryLabel() }}</span>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-slate-900">{{ $service->name }}</h3>
                        <p class="mt-1 line-clamp-2 flex-1 text-sm text-slate-500">{{ $service->description ?: 'Perawatan sepatu profesional dengan hasil maksimal.' }}</p>
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3.5">
                            <div>
                                <p class="text-lg font-bold text-slate-900">{{ $service->formattedPrice() }}</p>
                                <p class="text-xs text-slate-400">± {{ $service->estimated_days }} hari · {{ $service->penjual?->shop_name ?: $service->penjual?->name }}</p>
                            </div>
                            <a href="{{ route('pembeli.orders.create', ['service' => $service->id]) }}" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100">
                                Pesan <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($history->isNotEmpty())
        <div class="mt-8 rounded-2xl border border-slate-200/70 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Riwayat terakhir</h2>
                    <p class="text-xs text-slate-500">Pesanan terakhir yang kamu buat.</p>
                </div>
                <a href="{{ route('pembeli.orders.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">Semua pesanan</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach ($history as $order)
                    <a href="{{ route('pembeli.orders.show', $order) }}" class="flex items-center justify-between gap-3 px-5 py-3.5 transition hover:bg-slate-50/60">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                <x-icon name="shoe" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $order->service?->name }}</p>
                                <p class="text-xs text-slate-400">{{ $order->invoice_number }} · {{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <x-status-badge :order="$order" />
                            <span class="text-sm font-semibold text-slate-800">{{ $order->formattedTotal() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endsection
