@extends('layouts.guest')

@section('title', 'Masuk')
@section('heading', 'Masuk')

@section('content')
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Masuk ke akun Anda</h2>
        <p class="mt-1.5 text-sm text-slate-500">Kelola pesanan cuci sepatu Anda dalam satu dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" autocomplete="off" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" required autocomplete="off"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                placeholder="Masukkan email Anda">
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
            </div>
            <input id="password" name="password" type="password" required autocomplete="off"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                placeholder="Masukkan password Anda">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            Ingat saya
        </label>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            Masuk
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        
        <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:text-blue-800"></a>
    </p>

    <p class="mt-4 text-center text-sm">
        <a href="{{ route('home') }}" class="font-semibold text-blue-700 hover:text-blue-800">Kembali</a>
    </p>
@endsection
