@extends('layouts.app')

@section('title', 'Kelola Layanan')
@section('heading', 'Kelola Layanan')
@section('subheading', 'Semua layanan cuci sepatu dari mitra penjual.')

@section('actions')
    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
        <x-icon name="plus" class="h-4 w-4" /> Tambah layanan
    </a>
@endsection

@section('content')
    <div class="rounded-2xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-5">
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama layanan..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                </div>
                <select name="penjual" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    <option value="">Semua penjual</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected((string) request('penjual') === (string) $seller->id)>{{ $seller->shop_name ?: $seller->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    <x-icon name="filter" class="h-4 w-4" /> Filter
                </button>
            </form>
        </div>

        @if ($services->isEmpty())
            <div class="p-5">
                <x-empty-state icon="services" title="Belum ada layanan" description="Tambahkan layanan pertama untuk mitra penjual.">
                    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                        <x-icon name="plus" class="h-4 w-4" /> Tambah layanan
                    </a>
                </x-empty-state>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Layanan</th>
                            <th class="px-5 py-3">Penjual</th>
                            <th class="px-5 py-3">Harga</th>
                            <th class="px-5 py-3">Estimasi</th>
                            <th class="px-5 py-3">Pesanan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($services as $service)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $service->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $service->categoryLabel() }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-700">{{ $service->penjual?->shop_name ?: $service->penjual?->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $service->penjual?->email }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-800">{{ $service->formattedPrice() }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $service->estimated_days }} hari</td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $service->orders_count }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                        {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.services.edit', $service) }}" title="Edit" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 hover:text-blue-700">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form method="POST" action="{{ route('admin.services.toggle', $service) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 {{ $service->is_active ? 'hover:text-amber-600' : 'hover:text-emerald-600' }}">
                                                <x-icon :name="$service->is_active ? 'ban' : 'check'" class="h-4 w-4" />
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm="Hapus layanan {{ $service->name }}?" title="Hapus" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection
