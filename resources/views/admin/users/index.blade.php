@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('heading', 'Kelola Pengguna')
@section('subheading', 'Kelola akun pembeli, penjual, admin, dan pengunjung website.')

@section('actions')
    <a href="{{ route('admin.services.index') }}" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:inline-flex">
        <x-icon name="services" class="h-4 w-4 text-blue-600" /> Layanan
    </a>
@endsection

@section('content')
    <div class="mb-5 grid gap-3 sm:grid-cols-2">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-white px-4 py-3 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600"><x-icon name="shield" class="h-4 w-4" /></span>
            <div><p class="text-xs text-slate-500">Admin</p><p class="text-lg font-bold text-slate-900">{{ $counts['admin'] ?? 0 }}</p></div>
        </div>
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-white px-4 py-3 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600"><x-icon name="users" class="h-4 w-4" /></span>
            <div><p class="text-xs text-slate-500">Pengunjung</p><p class="text-lg font-bold text-slate-900">{{ $counts['pengunjung'] ?? 0 }}</p></div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-5">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau toko..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                </div>
                <select name="role" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    <option value="">Semua role</option>
                    @foreach (\App\Models\User::ROLES as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    <x-icon name="filter" class="h-4 w-4" /> Filter
                </button>
            </form>
        </div>

        @if ($users->isEmpty())
            <div class="p-5">
                <x-empty-state icon="users" title="Pengguna tidak ditemukan" description="Coba ubah kata kunci atau filter role." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3">Kontak</th>
                            <th class="px-5 py-3">Role &amp; Status</th>
                            <th class="px-5 py-3">Bergabung</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr class="align-top transition hover:bg-slate-50/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">{{ $user->initials() }}</span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800">{{ $user->name }} @if ($user->is(auth()->user()))<span class="ml-1 rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500">Anda</span>@endif</p>
                                            <p class="truncate text-xs text-slate-400">{{ $user->shop_name ?: '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-700">{{ $user->email }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->phone ?: 'Belum ada nomor' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex flex-col gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium shadow-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                            @foreach (\App\Models\User::ROLES as $value => $label)
                                                <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center gap-2">
                                            <input type="hidden" name="is_active" value="0">
                                            <label class="flex items-center gap-1.5 text-xs text-slate-500">
                                                <input type="checkbox" name="is_active" value="1" @checked($user->is_active) class="h-3.5 w-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                Aktif
                                            </label>
                                            <button type="submit" class="rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-200">Simpan</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 {{ $user->is_active ? 'hover:text-amber-600' : 'hover:text-emerald-600' }}">
                                                <x-icon :name="$user->is_active ? 'ban' : 'check'" class="h-4 w-4" />
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm="Hapus pengguna {{ $user->name }}?" title="Hapus" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
