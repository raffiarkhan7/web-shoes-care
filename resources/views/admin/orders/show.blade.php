@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('heading', 'Detail Pesanan')
@section('subheading', $order->invoice_number)

@section('actions')
    <a href="{{ route('admin.orders.index') }}" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:inline-flex">
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
                    <x-status-badge :order="$order" />
                </div>

                <div class="mt-6 grid gap-5 border-t border-slate-100 pt-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Layanan</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $order->service?->name }}</p>
                        <p class="text-sm text-slate-500">{{ $order->quantity }} pasang × {{ $order->service?->formattedPrice() }}</p>
                        <p class="mt-1 text-xs text-slate-400">Estimasi {{ $order->service?->estimated_days }} hari kerja</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Penjual</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $order->penjual?->shop_name ?: $order->penjual?->name }}</p>
                        <p class="text-sm text-slate-500">{{ $order->penjual?->email }}</p>
                        <p class="text-xs text-slate-400">{{ $order->penjual?->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pembeli</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $order->user?->name }}</p>
                        <p class="text-sm text-slate-500">{{ $order->user?->email }}</p>
                        <p class="text-xs text-slate-400">{{ $order->user?->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                        <p class="mt-1.5 text-2xl font-bold text-blue-700">{{ $order->formattedTotal() }}</p>
                        <p class="mt-1 text-xs font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $order->paymentStatusLabel() }} · {{ $order->paymentMethodLabel() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Informasi antar jemput</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-slate-500"><x-icon name="calendar" class="h-3.5 w-3.5" /> Jadwal</p>
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
                            <p class="text-xs font-semibold text-amber-700">Catatan pembeli</p>
                            <p class="mt-1 text-sm text-amber-800">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold text-slate-900">Perbarui status</h3>
                <p class="mt-1 text-xs text-slate-500">Perubahan langsung terlihat oleh pembeli dan penjual.</p>

                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700">Status pesanan</label>
                        <select id="status" name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            @foreach (\App\Models\Order::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="mb-1.5 block text-sm font-semibold text-slate-700">Status pembayaran</label>
                        <select id="payment_status" name="payment_status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            @foreach (\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($order->payment_status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        <x-icon name="check" class="h-4 w-4" /> Simpan perubahan
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-slate-900 p-5 text-white shadow-sm">
                <h3 class="text-sm font-bold">Alur pesanan</h3>
                <ol class="mt-4 space-y-3">
                    @php($steps = ['pending', 'confirmed', 'picked_up', 'processing', 'ready', 'delivering', 'completed'])
                    @php($currentIndex = array_search($order->status, $steps, true))
                    @foreach ($steps as $index => $step)
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $order->status === 'cancelled' ? 'bg-slate-700 text-slate-400' : ($currentIndex !== false && $index <= $currentIndex ? 'bg-amber-400 text-blue-950' : 'bg-slate-700 text-slate-400') }}">
                                @if ($currentIndex !== false && $index < $currentIndex)
                                    <x-icon name="check" class="h-3.5 w-3.5" />
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </span>
                            <span class="text-xs font-medium {{ $order->status === 'cancelled' ? 'text-slate-500' : ($currentIndex !== false && $index <= $currentIndex ? 'text-white' : 'text-slate-400') }}">{{ \App\Models\Order::STATUSES[$step] }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
@endsection
