<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
</div>
@extends('layouts.app')

@section('title', 'Konten Website')
@section('heading', 'Konten Website')
@section('subheading', 'Simpan perubahan sebagai draft, pratinjau, lalu publikasikan ke landing page.')

@section('actions')
    <a href="{{ route('home', ['preview' => 1]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Preview Website</a>
@endsection

@section('content')
    <div class="mx-auto mb-5 max-w-4xl rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        Status: <span class="font-semibold">{{ $isPublished ? 'sudah pernah dipublikasikan' : 'belum ada publikasi tersimpan' }}</span>. Pengunjung melihat versi published; draft dapat diperiksa lewat Preview Website.
    </div>
    <form method="POST" action="{{ route('admin.website.update') }}" enctype="multipart/form-data" class="mx-auto max-w-4xl space-y-6">
        @csrf
        @method('PUT')
        <section class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-base font-bold text-slate-900">Hero Section</h2>
            <p class="mt-1 text-sm text-slate-500">Bagian pembuka yang tampil di landing page.</p>
            <div class="mt-5 grid gap-5">
                @foreach (['hero_eyebrow' => 'Teks kecil', 'hero_title' => 'Judul utama', 'hero_description' => 'Deskripsi', 'hero_proof' => 'Catatan harga'] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        @if ($field === 'hero_description' || $field === 'hero_title')
                            <textarea id="{{ $field }}" name="{{ $field }}" rows="{{ $field === 'hero_title' ? 2 : 3 }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">{{ old($field, $content[$field]) }}</textarea>
                        @else
                            <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $content[$field]) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        @endif
                    </div>
                @endforeach
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach (['hero_image' => 'Foto hero', 'logo' => 'Logo'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                            <img src="{{ $content[$field] }}" alt="{{ $label }} saat ini" class="mb-3 h-36 w-full rounded-xl border border-slate-200 bg-slate-50 object-contain p-2">
                            <input id="{{ $field }}" name="{{ $field }}" type="file" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-800">
                            @error($field)<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-base font-bold text-slate-900">CTA, kontak, dan footer</h2>
            <div class="mt-5 grid gap-5">
                @foreach (['cta_title' => 'Judul ajakan', 'footer_description' => 'Deskripsi footer', 'contact_label' => 'Label kontak', 'contact_note' => 'Informasi kontak'] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        <textarea id="{{ $field }}" name="{{ $field }}" rows="2" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">{{ old($field, $content[$field]) }}</textarea>
                    </div>
                @endforeach
                <div>
                    <label for="whatsapp_number" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor WhatsApp admin</label>
                    <input id="whatsapp_number" name="whatsapp_number" inputmode="tel" value="{{ old('whatsapp_number', $content['whatsapp_number'] ?? config('services.whatsapp.admin_number')) }}" placeholder="6281234567890" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    <p class="mt-1 text-xs text-slate-500">Gunakan kode negara, misalnya 62. Boleh diawali tanda +.</p>
                    @error('whatsapp_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
            <form method="POST" action="{{ route('admin.website.discard') }}">
                @csrf
                <button type="submit" data-confirm="Batalkan semua draft dan pulihkan versi terpublikasi?" class="inline-flex w-full justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
            </form>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Simpan Draft</button>
                <button type="submit" name="publish" value="1" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Publikasikan</button>
            </div>
        </div>
    </form>
@endsection
