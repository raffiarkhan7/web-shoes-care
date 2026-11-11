@extends('layouts.app')

@section('title', 'Dashboard Penjual')
@section('heading', 'Dashboard Penjual')
@section('subheading', auth()->user()->shop_name ?: 'Kelola layanan dan pesanan toko Anda.')

@section('actions')
    <a href="{{ route('penjual.services.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
        <x-icon name="plus" class="h-4 w-4" /> Layanan baru
    </a>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Layanan Aktif" :value="$stats['active_services'].' / '.$stats['services']" icon="services" tone="teal" hint="Layanan tampil di katalog" />
        <x-stat-card label="Pesanan Berjalan" :value="number_format($stats['incoming'])" icon="truck" tone="sky" hint="Sedang diproses / diantar" />
        <x-stat-card label="Menunggu Konfirmasi" :value="number_format($stats['pending'])" icon="clock" tone="amber" hint="Perlu segera ditindaklanjuti" />
        <x-stat-card label="Pendapatan Lunas" :value="'Rp '.number_format($stats['revenue'], 0, ',', '.')" icon="money" tone="violet" :hint="$stats['completed'].' pesanan selesai'" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pesanan masuk terbaru</h2>
                        <p class="text-xs text-slate-500">Pesanan yang ditujukan ke toko Anda.</p>
                    </div>
                    <a href="{{ route('penjual.orders.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">Lihat semua</a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="p-5">
                        <x-empty-state icon="orders" title="Belum ada pesanan masuk" description="Pastikan layanan Anda aktif agar muncul di katalog pembeli." />
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
                                            <a href="{{ route('penjual.orders.show', $order) }}" class="font-semibold text-blue-700 hover:text-blue-800">{{ $order->invoice_number }}</a>
                                            <p class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <p class="font-medium text-slate-800">{{ $order->user?->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $order->user?->phone }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <p class="font-medium text-slate-800">{{ $order->service?->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $order->quantity }} pasang</p>
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
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Layanan saya</h2>
                    <a href="{{ route('penjual.services.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">Kelola</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($services as $service)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $service->name }}</p>
                                <p class="text-xs text-slate-500">{{ $service->formattedPrice() }} · {{ $service->orders_count }} pesanan</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                {{ $service->is_active ? 'Aktif' : 'Off' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada layanan. Tambahkan layanan pertama Anda.</p>
                    @endforelse
                </div>

                <a href="{{ route('penjual.services.create') }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
                    <x-icon name="plus" class="h-4 w-4" /> Tambah layanan
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200/70 bg-gradient-to-br from-amber-300 to-amber-500 p-5 text-blue-950 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-950/10">
                    <x-icon name="truck" class="h-5 w-5" />
                </span>
                <h2 class="mt-3 text-base font-bold">Antar jemput lebih rapi</h2>
                <p class="mt-1 text-xs font-medium text-blue-900/80">Perbarui status pesanan secara berkala agar pembeli tahu posisi sepatunya.</p>
                <a href="{{ route('penjual.orders.index') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-blue-950 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-900">
                    Buka pesanan <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>
        </div>
    </div>
@endsection
