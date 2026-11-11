@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('heading', 'Detail Pesanan')
@section('subheading', $order->invoice_number)

@section('actions')
    <a href="{{ route('pembeli.orders.index') }}" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:inline-flex">
        <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
    </a>
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Invoice</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $order->invoice_number }}</h2>
                        <p class="mt-1 text-xs text-slate-500">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <x-status-badge :order="$order" />
                        <p class="mt-2 text-2xl font-bold text-blue-700">{{ $order->formattedTotal() }}</p>
                        <p class="text-xs font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $order->paymentStatusLabel() }}</p>
                    </div>
                </div>

                @if ($order->status === 'cancelled')
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <x-icon name="ban" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" />
                        <p>Pesanan ini telah dibatalkan. Silakan buat pesanan baru jika masih membutuhkan layanan.</p>
                    </div>
                @else
                    @php($steps = ['pending', 'confirmed', 'picked_up', 'processing', 'ready', 'delivering', 'completed'])
                    @php($currentIndex = array_search($order->status, $steps, true))
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status perjalanan sepatu</p>
                        <ol class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($steps as $index => $step)
                                @php($isDone = $currentIndex !== false && $index < $currentIndex)
                                @php($isCurrent = $currentIndex !== false && $index === $currentIndex)
                                <li class="flex items-center gap-3 rounded-xl border px-3.5 py-2.5 {{ $isCurrent ? 'border-blue-300 bg-blue-50' : ($isDone ? 'border-emerald-100 bg-emerald-50/50' : 'border-slate-100 bg-slate-50/50') }}">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $isCurrent ? 'bg-blue-700 text-white' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500') }}">
                                        @if ($isDone)
                                            <x-icon name="check" class="h-3.5 w-3.5" />
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <span class="text-xs font-semibold {{ $isCurrent ? 'text-blue-800' : ($isDone ? 'text-emerald-700' : 'text-slate-500') }}">{{ \App\Models\Order::STATUSES[$step] }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Rincian layanan</h3>
                <div class="mt-4 flex items-start gap-4 rounded-xl bg-slate-50 p-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-emerald-500 text-white">
                        <x-icon name="shoe" class="h-6 w-6" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-slate-900">{{ $order->service?->name }}</p>
                        <p class="text-xs text-slate-500">{{ $order->service?->categoryLabel() }} · {{ $order->quantity }} pasang</p>
                        <p class="mt-1 text-xs text-slate-400">Estimasi {{ $order->service?->estimated_days }} hari kerja</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-slate-800">{{ $order->service?->formattedPrice() }}</p>
                        <p class="text-xs text-slate-400">per pasang</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2 rounded-xl border border-slate-100 p-4 text-sm">
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span class="font-medium text-slate-700">{{ $order->formattedTotal() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Biaya antar jemput</span>
                        <span class="font-medium text-emerald-600">Gratis</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-2 text-slate-900">
                        <span class="font-semibold">Total</span>
                        <span class="text-lg font-bold text-blue-700">{{ $order->formattedTotal() }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Informasi antar jemput</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-slate-500"><x-icon name="calendar" class="h-3.5 w-3.5" /> Jadwal jemput</p>
                        <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $order->pickup_date->format('d M Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $order->pickup_time }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-slate-500"><x-icon name="truck" class="h-3.5 w-3.5" /> Metode</p>
                        <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $order->pickupMethodLabel() }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-slate-500"><x-icon name="map-pin" class="h-3.5 w-3.5" /> Alamat</p>
                        <p class="mt-1.5 text-sm text-slate-800">{{ $order->pickup_address }}</p>
                    </div>
                    @if ($order->notes)
                        <div class="rounded-xl bg-amber-50 p-4 ring-1 ring-amber-100 sm:col-span-2">
                            <p class="text-xs font-semibold text-amber-700">Catatan</p>
                            <p class="mt-1 text-sm text-amber-800">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Toko Anda</h3>
                <div class="mt-4 flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <x-icon name="store" class="h-5 w-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-800">{{ $order->penjual?->shop_name ?: $order->penjual?->name }}</p>
                        <p class="text-xs text-slate-500">{{ $order->penjual?->phone ?: 'Hubungi via aplikasi' }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                    <p class="flex items-center gap-2"><x-icon name="user" class="h-3.5 w-3.5" /> {{ $order->penjual?->name }}</p>
                    <p class="flex items-center gap-2"><x-icon name="phone" class="h-3.5 w-3.5" /> {{ $order->penjual?->phone ?: '—' }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Pembayaran</h3>
                <div class="mt-3 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <span class="text-xs font-medium text-slate-500">{{ $order->paymentMethodLabel() }}</span>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                        {{ $order->paymentStatusLabel() }}
                    </span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-400">Pembayaran dikonfirmasi oleh penjual setelah sepatu dijemput atau sesuai kesepakatan.</p>
            </div>

            @if ($order->isCancellable())
                <form method="POST" action="{{ route('pembeli.orders.cancel', $order) }}">
                    @csrf
                    <button type="submit" data-confirm="Batalkan pesanan {{ $order->invoice_number }}?" class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                        <x-icon name="ban" class="h-4 w-4" /> Batalkan pesanan
                    </button>
                </form>
            @endif

            <div class="rounded-2xl bg-gradient-to-br from-blue-900 to-emerald-900 p-5 text-white shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                    <x-icon name="shield" class="h-5 w-5 text-amber-300" />
                </span>
                <h3 class="mt-3 text-sm font-bold">Garansi Shaos Care</h3>
                <p class="mt-1 text-xs leading-relaxed text-blue-100/75">Jika hasil kurang memuaskan, ajukan cuci ulang gratis dalam 2×24 jam setelah sepatu diterima.</p>
            </div>
        </div>
    </div>
@endsection
