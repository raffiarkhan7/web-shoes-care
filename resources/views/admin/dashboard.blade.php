@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('heading', 'Dashboard Admin')
@section('subheading', 'Ringkasan performa Shaos Care secara keseluruhan.')

@section('actions')
    <a href="{{ route('admin.orders.index') }}" class="hidden items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 sm:inline-flex">
        Lihat semua pesanan <x-icon name="arrow-right" class="h-4 w-4" />
    </a>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total Pembeli" :value="number_format($stats['pembeli'])" icon="users" tone="sky" hint="Akun pembeli terdaftar" />
        <x-stat-card label="Total Penjual" :value="number_format($stats['penjual'])" icon="store" tone="violet" hint="Mitra toko aktif" />
        <x-stat-card label="Total Pesanan" :value="number_format($stats['orders'])" icon="orders" tone="amber" :hint="$stats['pending'].' menunggu konfirmasi'" />
        <x-stat-card label="Pendapatan Lunas" :value="'Rp '.number_format($stats['revenue'], 0, ',', '.')" icon="money" tone="teal" hint="Dari pesanan berstatus lunas" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pesanan terbaru</h2>
                        <p class="text-xs text-slate-500">6 pesanan terakhir yang masuk.</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800">Lihat semua</a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="p-5">
                        <x-empty-state icon="orders" title="Belum ada pesanan" description="Pesanan dari pembeli akan tampil di sini." />
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Invoice</th>
                                    <th class="px-5 py-3">Pembeli</th>
                                    <th class="px-5 py-3">Layanan</th>
                                    <th class="px-5 py-3">Total</th>
                                    <th class="px-5 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($recentOrders as $order)
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-teal-700 hover:text-teal-800">{{ $order->invoice_number }}</a>
                                            <p class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <p class="font-medium text-slate-800">{{ $order->user?->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $order->user?->phone }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <p class="font-medium text-slate-800">{{ $order->service?->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $order->quantity }} pasang · {{ $order->penjual?->shop_name ?? $order->penjual?->name }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 font-semibold text-slate-800">{{ $order->formattedTotal() }}</td>
                                        <td class="whitespace-nowrap px-5 py-3.5"><x-status-badge :order="$order" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Distribusi status pesanan</h2>
                        <p class="text-xs text-slate-500">Gambaran posisi seluruh pesanan saat ini.</p>
                    </div>
                    <x-icon name="filter" class="h-5 w-5 text-slate-300" />
                </div>
                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    @foreach (\App\Models\Order::STATUSES as $status => $label)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/60 px-3.5 py-2.5">
                            <span class="text-xs font-medium text-slate-600">{{ $label }}</span>
                            <span class="rounded-full bg-white px-2.5 py-0.5 text-xs font-bold text-slate-800 ring-1 ring-slate-200">{{ $statusBreakdown[$status] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-900">Layanan terpopuler</h2>
                <p class="text-xs text-slate-500">Berdasarkan jumlah pesanan.</p>

                <div class="mt-4 space-y-4">
                    @forelse ($topServices as $service)
                        @php($max = max($topServices->max('orders_count'), 1))
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-medium text-slate-700">{{ $service->name }}</span>
                                <span class="shrink-0 text-xs font-semibold text-slate-500">{{ $service->orders_count }} pesanan</span>
                            </div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-emerald-400" style="width: {{ max(6, round(($service->orders_count / $max) * 100)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada data layanan.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-gradient-to-br from-teal-900 to-emerald-900 p-5 text-white shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                    <x-icon name="services" class="h-5 w-5 text-amber-300" />
                </span>
                <h2 class="mt-3 text-base font-bold">Kelola layanan</h2>
                <p class="mt-1 text-xs leading-relaxed text-teal-100/75">{{ $stats['services'] }} layanan terdaftar dari seluruh mitra penjual. Pastikan harga dan statusnya selalu relevan.</p>
                <a href="{{ route('admin.services.index') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-amber-400 px-3 py-2 text-xs font-bold text-teal-950 transition hover:bg-amber-300">
                    Buka manajemen layanan <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>
        </div>
    </div>
@endsection
