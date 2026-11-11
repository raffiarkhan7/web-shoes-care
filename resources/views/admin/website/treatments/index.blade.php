<div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
</div>
@extends('layouts.app')

@section('title', 'Treatment Landing')
@section('heading', 'Treatment Landing')
@section('subheading', 'Katalog ini terhubung dengan daftar treatment dan harga pada landing page.')

@section('actions')
    <a href="{{ route('admin.website.treatments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">Tambah treatment</a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm">
        @forelse ($treatments as $treatment)
            <div class="flex flex-col gap-4 border-b border-slate-100 p-4 last:border-0 sm:flex-row sm:items-center sm:p-5">
                <img src="{{ $treatment->image }}" alt="{{ $treatment->image_alt }}" class="h-24 w-32 rounded-xl bg-slate-100 object-cover">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ $treatment->category }}</p>
                    <h2 class="mt-1 font-bold text-slate-900">{{ $treatment->name }}</h2>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $treatment->description }}</p>
                </div>
                <div class="flex items-center justify-between gap-4 sm:justify-end">
                    <div class="text-right"><p class="font-bold text-slate-900">Rp{{ number_format($treatment->price, 0, ',', '.') }}</p><p class="text-xs text-slate-500">Urutan {{ $treatment->sort_order }} · {{ $treatment->is_active ? 'Tampil' : 'Disembunyikan' }}</p></div>
                    <a href="{{ route('admin.website.treatments.edit', $treatment) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                    <form method="POST" action="{{ route('admin.website.treatments.destroy', $treatment) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" data-confirm="Hapus {{ $treatment->name }} dari landing page?" class="rounded-lg border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center"><p class="font-semibold text-slate-800">Belum ada treatment aktif</p><p class="mt-1 text-sm text-slate-500">Treatment yang dihapus tidak ditampilkan pada katalog landing.</p></div>
        @endforelse
    </div>
@endsection
