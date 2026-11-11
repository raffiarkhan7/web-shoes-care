@extends('layouts.app')

@section('title', 'Layanan Saya')
@section('heading', 'Layanan Saya')
@section('subheading', 'Atur layanan cuci sepatu yang tampil di katalog pembeli.')

@section('actions')
    <a href="{{ route('penjual.services.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
        <x-icon name="plus" class="h-4 w-4" /> Tambah layanan
    </a>
@endsection

@section('content')
    @if ($services->isEmpty())
        <x-empty-state icon="services" title="Belum ada layanan" description="Tambahkan layanan pertama agar pembeli bisa memesan cuci sepatu di toko Anda.">
            <a href="{{ route('penjual.services.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                <x-icon name="plus" class="h-4 w-4" /> Tambah layanan
            </a>
        </x-empty-state>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($services as $service)
                <div class="flex flex-col rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <x-icon name="sparkles" class="h-5 w-5" />
                        </span>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <h3 class="mt-4 text-base font-bold text-slate-900">{{ $service->name }}</h3>
                    <p class="mt-0.5 text-xs font-medium uppercase tracking-wide text-blue-600">{{ $service->categoryLabel() }}</p>
                    <p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-500">{{ $service->description ?: 'Belum ada deskripsi layanan.' }}</p>

                    <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-4">
                        <div>
                            <p class="text-lg font-bold text-slate-900">{{ $service->formattedPrice() }}</p>
                            <p class="text-xs text-slate-400">{{ $service->estimated_days }} hari · {{ $service->orders_count }} pesanan</p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('penjual.services.edit', $service) }}" title="Edit" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 hover:text-blue-700">
                                <x-icon name="edit" class="h-4 w-4" />
                            </a>
                            <form method="POST" action="{{ route('penjual.services.toggle', $service) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="{{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 {{ $service->is_active ? 'hover:text-amber-600' : 'hover:text-emerald-600' }}">
                                    <x-icon :name="$service->is_active ? 'ban' : 'check'" class="h-4 w-4" />
                                </button>
                            </form>
                            <form method="POST" action="{{ route('penjual.services.destroy', $service) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-confirm="Hapus layanan {{ $service->name }}?" title="Hapus" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @endif
@endsection
