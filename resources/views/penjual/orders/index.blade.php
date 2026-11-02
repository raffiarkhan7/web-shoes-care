@extends('layouts.app')

@section('title', 'Pesanan Masuk')
@section('heading', 'Pesanan Masuk')
@section('subheading', 'Kelola pesanan cuci sepatu untuk toko Anda.')

@section('content')
    <div class="rounded-2xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-5">
            <form method="GET" action="{{ route('penjual.orders.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari invoice atau nama pembeli..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10">
                </div>
                <select name="status" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Order::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">
                    <x-icon name="filter" class="h-4 w-4" /> Filter
                </button>
            </form>
        </div>

        @if ($orders->isEmpty())
            <div class="p-5">
                <x-empty-state icon="orders" title="Belum ada pesanan" description="Pesanan dari pembeli akan muncul di sini." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Invoice</th>
                            <th class="px-5 py-3">Pembeli</th>
                            <th class="px-5 py-3">Layanan</th>
                            <th class="px-5 py-3">Jadwal Jemput</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Bayar</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($orders as $order)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $order->invoice_number }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-800">{{ $order->user?->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->user?->phone }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-800">{{ $order->service?->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->quantity }} pasang</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-slate-700">{{ $order->pickup_date->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->pickup_time }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-800">{{ $order->formattedTotal() }}</td>
                                <td class="whitespace-nowrap px-5 py-4"><x-status-badge :order="$order" /></td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                        {{ $order->paymentStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('penjual.orders.show', $order) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-teal-700">
                                        <x-icon name="eye" class="h-3.5 w-3.5" /> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
