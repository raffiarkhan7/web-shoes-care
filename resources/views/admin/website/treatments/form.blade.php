<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
@extends('layouts.app')

@section('title', $treatment->exists ? 'Edit Treatment' : 'Tambah Treatment')
@section('heading', $treatment->exists ? 'Edit Treatment' : 'Tambah Treatment')
@section('subheading', 'Perubahan akan terlihat di katalog treatment dan daftar harga landing page.')

@section('content')
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="mx-auto max-w-3xl rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @if ($method !== 'POST') @method($method) @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama treatment</label>
                <input id="name" name="name" value="{{ old('name', $treatment->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category" class="mb-1.5 block text-sm font-semibold text-slate-700">Kategori</label>
                <input id="category" name="category" value="{{ old('category', $treatment->category) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('description', $treatment->description) }}</textarea>
            </div>
            <div>
                <label for="problems" class="mb-1.5 block text-sm font-semibold text-slate-700">Cocok untuk</label>
                <textarea id="problems" name="problems" rows="4" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('problems', implode("\n", $treatment->problems ?? [])) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Satu item per baris.</p>
            </div>
            <div>
                <label for="details" class="mb-1.5 block text-sm font-semibold text-slate-700">Rincian treatment</label>
                <textarea id="details" name="details" rows="4" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('details', implode("\n", $treatment->details ?? [])) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Satu item per baris.</p>
            </div>
            <div>
                <label for="price" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="0" value="{{ old('price', $treatment->price) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Urutan tampilan</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $treatment->sort_order ?? 0) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <div class="sm:col-span-2">
                <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">Foto treatment</label>
                @if ($treatment->image)<img src="{{ $treatment->image }}" alt="Foto saat ini" class="mb-3 h-40 w-full rounded-xl bg-slate-100 object-cover">@endif
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(!$treatment->exists) class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-800">
                @error('image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="image_alt" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi foto</label>
                <input id="image_alt" name="image_alt" value="{{ old('image_alt', $treatment->image_alt) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 sm:col-span-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $treatment->is_active ?? true)) class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                Tampilkan di landing page
            </label>
        </div>
        <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.website.treatments.index') }}" class="inline-flex justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
            <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Simpan treatment</button>
        </div>
    </form>
@endsection
