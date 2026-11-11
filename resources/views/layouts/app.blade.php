<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Shaos Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100 font-sans text-slate-800 antialiased">
    @php($user = auth()->user())

    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-blue-950 text-white transition-transform duration-300 lg:translate-x-0">
        <div class="flex items-center justify-between px-6 pb-4 pt-6">
            <a href="{{ route($user->dashboardRoute()) }}" class="flex items-center gap-3">
                <img src="/landing/assets/logo.png" alt="Awash Care" class="h-auto w-36 rounded bg-white px-2 py-1">
            </a>
            <button id="sidebar-close" type="button" class="rounded-lg p-2 text-blue-200 hover:bg-white/10 lg:hidden" aria-label="Tutup menu">
                <x-icon name="close" class="h-5 w-5" />
            </button>
        </div>

        <div class="mx-4 mt-2 rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-300 to-amber-500 text-sm font-bold text-blue-950">
                    {{ $user->initials() }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                    <p class="text-xs text-blue-200/70">{{ $user->roleLabel() }}{{ $user->isPenjual() && $user->shop_name ? ' · '.$user->shop_name : '' }}</p>
                </div>
            </div>
        </div>

        <nav class="mt-6 flex-1 space-y-1 overflow-y-auto px-4 pb-6">
            <p class="px-3.5 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-300/50">Menu Utama</p>

            @if ($user->isAdmin())
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="dashboard">Dashboard</x-nav-link>
                <x-nav-link :href="route('admin.website.edit')" :active="request()->routeIs('admin.website.*')" icon="edit">Konten Website</x-nav-link>
                <x-nav-link :href="route('admin.website.treatments.index')" :active="request()->routeIs('admin.website.treatments.*')" icon="services">Treatment Landing</x-nav-link>
                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="users">Pengguna</x-nav-link>
                <x-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')" icon="services">Layanan</x-nav-link>
                <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" icon="orders">Semua Pesanan</x-nav-link>
            @elseif ($user->isPenjual())
                <x-nav-link :href="route('penjual.dashboard')" :active="request()->routeIs('penjual.dashboard')" icon="dashboard">Dashboard</x-nav-link>
                <x-nav-link :href="route('penjual.services.index')" :active="request()->routeIs('penjual.services.*')" icon="services">Layanan Saya</x-nav-link>
                <x-nav-link :href="route('penjual.orders.index')" :active="request()->routeIs('penjual.orders.*')" icon="orders">Pesanan Masuk</x-nav-link>
            @else
                <x-nav-link :href="route('pembeli.dashboard')" :active="request()->routeIs('pembeli.dashboard')" icon="dashboard">Dashboard</x-nav-link>
                <x-nav-link :href="route('pembeli.orders.create')" :active="request()->routeIs('pembeli.orders.create')" icon="plus">Pesan Layanan</x-nav-link>
                <x-nav-link :href="route('pembeli.orders.index')" :active="request()->routeIs('pembeli.orders.index') || request()->routeIs('pembeli.orders.show')" icon="history">Pesanan Saya</x-nav-link>
            @endif

            <div class="mt-6 rounded-2xl bg-blue-50 p-4 text-blue-950">
                <p class="text-sm font-bold">Antar jemput gratis</p>
                <p class="mt-1 text-xs font-medium text-blue-900/80">Untuk area kota dengan minimal 2 pasang sepatu. Cek layanan favoritmu sekarang.</p>
                @if ($user->isPembeli())
                    <a href="{{ route('pembeli.orders.create') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-950 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-900">
                        Pesan sekarang <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                    </a>
                @endif
            </div>
        </nav>

        <div class="border-t border-white/10 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-blue-100/75 transition hover:bg-rose-500/20 hover:text-white">
                    <x-icon name="logout" class="h-5 w-5" />
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="lg:pl-72">
        <header class="sticky top-0 z-20 border-b border-slate-200/70 bg-white/80 backdrop-blur">
            <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button id="sidebar-toggle" type="button" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden" aria-label="Buka menu">
                        <x-icon name="menu" class="h-5 w-5" />
                    </button>
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900 sm:text-xl">@yield('heading', 'Dashboard')</h1>
                        <p class="truncate text-xs text-slate-500 sm:text-sm">@yield('subheading', 'Selamat datang kembali di Shaos Care.')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @yield('actions')
                    <div class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm sm:flex">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-[11px] font-bold text-blue-700">{{ $user->initials() }}</span>
                        <span class="text-xs font-semibold text-slate-700">{{ $user->roleLabel() }}</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <x-icon name="ban" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" />
                    <p>{{ session('error') }}</p>
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
        </main>
    </div>
</body>
</html>
