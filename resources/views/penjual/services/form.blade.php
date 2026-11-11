@extends('layouts.app')

@section('title', $service->exists ? 'Edit Layanan' : 'Tambah Layanan')
@section('heading', $service->exists ? 'Edit Layanan' : 'Tambah Layanan')
@section('subheading', 'Lengkapi detail layanan cuci sepatu Anda.')

@section('content')
    <div class="mx-auto max-w-3xl">
        <form method="POST" action="{{ $action }}" class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div class="space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama layanan</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $service->name) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: Deep Clean">
                    </div>
                    <div>
                        <label for="category" class="mb-1.5 block text-sm font-semibold text-slate-700">Kategori</label>
                        <input id="category" name="category" type="text" value="{{ old('category', $service->category) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: Deep Clean">
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Jelaskan proses dan keunggulan layanan ini...">{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga per pasang (Rp)</label>
                        <input id="price" name="price" type="number" min="0" step="1000" value="{{ old('price', $service->price) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="35000">
                    </div>
                    <div>
                        <label for="estimated_days" class="mb-1.5 block text-sm font-semibold text-slate-700">Estimasi pengerjaan (hari)</label>
                        <input id="estimated_days" name="estimated_days" type="number" min="1" max="30" value="{{ old('estimated_days', $service->estimated_days ?: 3) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    </div>
                </div>

                <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-700">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true)) class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Tampilkan layanan ini di katalog pembeli
                </label>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('penjual.services.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    <x-icon name="check" class="h-4 w-4" /> Simpan layanan
                </button>
            </div>
        </form>
    </div>
@endsection
