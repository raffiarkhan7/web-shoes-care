<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') · Shaos Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="flex min-h-full">
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-blue-950 p-10 text-white lg:flex xl:p-14">

            <a href="{{ url('/') }}" class="relative inline-flex w-fit items-center rounded bg-white px-3 py-2">
                <img src="/landing/assets/logo.png" alt="Awash Care" class="h-auto w-40">
            </a>

            <div class="relative max-w-md">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-amber-200 ring-1 ring-white/15">
                    <x-icon name="sparkles" class="h-3.5 w-3.5" /> Antar jemput sepatu
                </span>
                <h1 class="mt-5 text-4xl font-bold leading-tight tracking-tight xl:text-5xl">Sepatu kotor?<br><span class="text-amber-300">Kami jemput, cuci, antar.</span></h1>
                <p class="mt-4 text-sm leading-relaxed text-blue-100/80">Satu platform untuk pembeli, penjual, dan admin Shaos Care. Pantau pesanan, kelola layanan, dan antar jemput sepatu tanpa ribet.</p>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white/5 p-3 ring-1 ring-white/10">
                        <p class="text-lg font-bold text-amber-300">3</p>
                        <p class="mt-0.5 text-[11px] text-blue-100/70">Role terintegrasi</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-3 ring-1 ring-white/10">
                        <p class="text-lg font-bold text-amber-300">24 jam</p>
                        <p class="mt-0.5 text-[11px] text-blue-100/70">Cuci express</p>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center gap-3 text-xs text-blue-200/60">
                <x-icon name="shield" class="h-4 w-4" /> Data dan transaksi Anda aman bersama Shaos Care.
            </div>
        </div>

        <div class="flex w-full flex-col justify-center px-5 py-10 sm:px-10 lg:w-1/2 lg:px-14 xl:px-24">
            <div class="mb-8 lg:hidden">
                <a href="{{ url('/') }}" class="inline-flex w-fit items-center rounded bg-white px-2 py-1">
                    <img src="/landing/assets/logo.png" alt="Awash Care" class="h-auto w-36">
                </a>
            </div>

            <div class="mx-auto w-full max-w-md">
                @if (session('success'))
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <div class="flex items-start gap-3">
                            <x-icon name="ban" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" />
                            <ul class="list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
