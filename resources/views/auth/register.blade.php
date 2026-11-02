@extends('layouts.guest')

@section('title', 'Daftar')
@section('heading', 'Daftar')

@section('content')
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Buat akun Shaos Care</h2>
        <p class="mt-1.5 text-sm text-slate-500">Daftar sebagai pembeli untuk memesan, atau sebagai penjual untuk membuka layanan cuci sepatu.</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="mt-7 space-y-4">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Saya mendaftar sebagai</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="role-option cursor-pointer rounded-xl border border-slate-300 bg-white p-3 transition has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 has-[:checked]:ring-2 has-[:checked]:ring-teal-500/20">
                    <input type="radio" name="role" value="pembeli" class="sr-only" @checked(old('role', 'pembeli') === 'pembeli')>
                    <span class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <x-icon name="user" class="h-4 w-4 text-teal-600" /> Pembeli
                    </span>
                    <span class="mt-1 block text-[11px] text-slate-500">Pesan layanan & antar jemput</span>
                </label>
                <label class="role-option cursor-pointer rounded-xl border border-slate-300 bg-white p-3 transition has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 has-[:checked]:ring-2 has-[:checked]:ring-teal-500/20">
                    <input type="radio" name="role" value="penjual" class="sr-only" @checked(old('role') === 'penjual')>
                    <span class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <x-icon name="store" class="h-4 w-4 text-teal-600" /> Penjual
                    </span>
                    <span class="mt-1 block text-[11px] text-slate-500">Buka layanan cuci sepatu</span>
                </label>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                    placeholder="Nama Anda">
            </div>
            <div>
                <label for="phone" class="mb-1.5 block text-sm font-semibold text-slate-700">No. WhatsApp</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required autocomplete="tel"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                    placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                placeholder="nama@email.com">
        </div>

        <div id="shop-name-field" class="hidden">
            <label for="shop_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama toko / layanan</label>
            <input id="shop_name" name="shop_name" type="text" value="{{ old('shop_name') }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                placeholder="Contoh: Shaos Clean Express">
        </div>

        <div>
            <label for="address" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
            <textarea id="address" name="address" rows="2" required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                placeholder="Alamat lengkap untuk antar jemput">{{ old('address') }}</textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                    placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                    placeholder="Ulangi password">
            </div>
        </div>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-500/20">
            Buat akun
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-800">Masuk di sini</a>
    </p>
@endsection
