@extends('layouts.app')

@section('title', 'Pesanan Saya')
@section('heading', 'Pesanan Saya')
@section('subheading', 'Pantau status cuci dan antar jemput sepatu Anda.')

@section('actions')
    <a href="{{ route('pembeli.orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">
        <x-icon name="plus" class="h-4 w-4" /> Pesan layanan
    </a>
@endsection

@section('content')
    <div class="mb-5 flex flex-wrap gap-2">
        <a href="{{ route('pembeli.orders.index') }}" class="rounded-full px-3.5 py-1.5 text-xs font-semibold ring-1 transition {{ request('status') ? 'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' : 'bg-teal-700 text-white ring-teal-700' }}">Semua</a>
        @foreach (\App\Models\Order::STATUSES as $value => $label)
            <a href="{{ route('pembeli.orders.index', ['status' => $value]) }}" class="rounded-full px-3.5 py-1.5 text-xs font-semibold ring-1 transition {{ request('status') === $value ? 'bg-teal-700 text-white ring-teal-700' : 'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' }}">{{ $label }}</a>
        @endforeach
    </div>

    @if ($orders->isEmpty())
        <x-empty-state icon="orders" title="Belum ada pesanan" description="Yuk, pesan layanan cuci sepatu pertamamu. Antar jemput gratis!">
            <a href="{{ route('pembeli.orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">
                <x-icon name="plus" class="h-4 w-4" /> Pesan layanan
            </a>
        </x-empty-state>
    @else
        <div class="space-y-3">
            @foreach ($orders as $order)
                <a href="{{ route('pembeli.orders.show', $order) }}" class="group block rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm transition hover:border-teal-300 hover:shadow-md">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-500 text-white shadow-sm">
                                <x-icon name="shoe" class="h-6 w-6" />
                            </span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-teal-700">{{ $order->service?->name }}</h3>
                                    <x-status-badge :order="$order" />
                                </div>
                                <p class="mt-0.5 text-xs font-medium text-slate-400">{{ $order->invoice_number }} · {{ $order->penjual?->shop_name ?: $order->penjual?->name }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5"><x-icon name="box" class="h-3.5 w-3.5" /> {{ $order->quantity }} pasang</span>
                                    <span class="flex items-center gap-1.5"><x-icon name="calendar" class="h-3.5 w-3.5" /> {{ $order->pickup_date->format('d M Y') }} · {{ $order->pickup_time }}</span>
                                    <span class="flex items-center gap-1.5"><x-icon name="truck" class="h-3.5 w-3.5" /> {{ $order->pickupMethodLabel() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center justify-between gap-4 sm:flex-col sm:items-end">
                            <p class="text-lg font-bold text-slate-900">{{ $order->formattedTotal() }}</p>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700">
                                Detail <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
