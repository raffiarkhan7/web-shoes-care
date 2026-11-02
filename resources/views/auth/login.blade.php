@extends('layouts.guest')

@section('title', 'Masuk')
@section('heading', 'Masuk')

@section('content')
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Masuk ke akun Anda</h2>
        <p class="mt-1.5 text-sm text-slate-500">Kelola pesanan cuci sepatu Anda dalam satu dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                placeholder="nama@email.com">
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                placeholder="••••••••">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
            Ingat saya
        </label>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-500/20">
            Masuk
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>
    </form>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Akun demo</p>
        <div class="mt-2.5 grid gap-2 sm:grid-cols-3">
            <button type="button" data-demo-email="admin@shaoscare.id" data-demo-password="password" class="demo-account rounded-xl border border-slate-200 px-3 py-2 text-left transition hover:border-teal-300 hover:bg-teal-50">
                <span class="block text-xs font-semibold text-slate-800">Admin</span>
                <span class="block text-[11px] text-slate-500">admin@shaoscare.id</span>
            </button>
            <button type="button" data-demo-email="penjual@shaoscare.id" data-demo-password="password" class="demo-account rounded-xl border border-slate-200 px-3 py-2 text-left transition hover:border-teal-300 hover:bg-teal-50">
                <span class="block text-xs font-semibold text-slate-800">Penjual</span>
                <span class="block text-[11px] text-slate-500">penjual@shaoscare.id</span>
            </button>
            <button type="button" data-demo-email="pembeli@shaoscare.id" data-demo-password="password" class="demo-account rounded-xl border border-slate-200 px-3 py-2 text-left transition hover:border-teal-300 hover:bg-teal-50">
                <span class="block text-xs font-semibold text-slate-800">Pembeli</span>
                <span class="block text-[11px] text-slate-500">pembeli@shaoscare.id</span>
            </button>
        </div>
        <p class="mt-2 text-[11px] text-slate-400">Klik salah satu akun untuk mengisi form otomatis. Password: <span class="font-semibold text-slate-500">password</span></p>
    </div>

    <p class="mt-6 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:text-teal-800">Daftar sekarang</a>
    </p>
@endsection
