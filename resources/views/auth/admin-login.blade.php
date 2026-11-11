<div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div>
@extends('layouts.guest')

@section('title', 'Login Admin')
@section('heading', 'Login Admin')

@section('content')
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700">AwashCare</p>
        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Masuk ke panel admin</h2>
        <p class="mt-1.5 text-sm text-slate-500">Kelola konten website dan aktivitas layanan.</p>
    </div>

    @if ($errors->any())
        <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" autocomplete="off" class="mt-7 space-y-5">
        @csrf
        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email admin</label>
            <input id="email" name="email" type="email" required autocomplete="off" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Masukkan email Anda">
        </div>
        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
            <div class="flex overflow-hidden rounded-xl border border-slate-300 bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10">
                <input id="password" name="password" type="password" required autocomplete="off" class="min-w-0 flex-1 border-0 px-4 py-3 text-sm outline-none" placeholder="Masukkan password Anda">
                <button type="button" id="toggle-password" class="px-4 text-xs font-semibold text-blue-700">Lihat</button>
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500"> Ingat saya</label>
        <button type="submit" class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">Masuk</button>
    </form>
    <p class="mt-6 text-center text-sm"><a href="{{ route('home') }}" class="font-semibold text-blue-700 hover:text-blue-800">Kembali</a></p>
    <script>
        document.getElementById('toggle-password')?.addEventListener('click', (event) => {
            const password = document.getElementById('password');
            const isHidden = password.type === 'password';
            password.type = isHidden ? 'text' : 'password';
            event.currentTarget.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
        });
    </script>
@endsection
