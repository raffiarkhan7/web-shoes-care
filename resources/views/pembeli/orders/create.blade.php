@extends('layouts.app')

@section('title', 'Pesan Layanan')
@section('heading', 'Pesan Layanan')
@section('subheading', 'Pilih layanan, jadwal jemput, dan kami datang ke lokasi Anda.')

@section('actions')
    <a href="{{ route('pembeli.orders.index') }}" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:inline-flex">
        <x-icon name="history" class="h-4 w-4" /> Pesanan saya
    </a>
@endsection

@section('content')
    @if ($services->isEmpty())
        <x-empty-state icon="services" title="Belum ada layanan tersedia" description="Penjual belum menambahkan layanan. Coba cek kembali nanti." />
    @else
        <form id="order-form" method="POST" action="{{ route('pembeli.orders.store') }}" class="grid gap-6 xl:grid-cols-3">
            @csrf

            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">1</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Pilih layanan</h2>
                            <p class="text-xs text-slate-500">Layanan aktif dari mitra penjual Shaos Care.</p>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($services as $service)
                            <label class="service-option cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/60 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input type="radio" name="service_id" value="{{ $service->id }}" class="sr-only"
                                    data-name="{{ $service->name }}"
                                    data-price="{{ $service->price }}"
                                    data-days="{{ $service->estimated_days }}"
                                    data-seller="{{ $service->penjual?->shop_name ?: $service->penjual?->name }}"
                                    @checked((string) old('service_id', $selectedService?->id) === (string) $service->id)>
                                <div class="flex items-start justify-between gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <x-icon name="sparkles" class="h-5 w-5" />
                                    </span>
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-500">{{ $service->categoryLabel() }}</span>
                                </div>
                                <p class="mt-3 text-sm font-bold text-slate-900">{{ $service->name }}</p>
                                <p class="mt-0.5 line-clamp-1 text-xs text-slate-500">{{ $service->penjual?->shop_name ?: $service->penjual?->name }}</p>
                                <div class="mt-3 flex items-end justify-between border-t border-slate-100 pt-2.5">
                                    <span class="text-sm font-bold text-blue-700">{{ $service->formattedPrice() }}</span>
                                    <span class="text-[11px] text-slate-400">± {{ $service->estimated_days }} hari</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">2</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Detail &amp; jadwal jemput</h2>
                            <p class="text-xs text-slate-500">Tentukan jumlah sepatu dan waktu penjemputan.</p>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Jumlah pasang</label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="qty-minus" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-50">−</button>
                                <input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" class="h-10 w-20 rounded-xl border border-slate-300 bg-white text-center text-sm font-semibold shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                <button type="button" id="qty-plus" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-50">+</button>
                            </div>
                        </div>

                        <div>
                            <label for="pickup_date" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal jemput</label>
                            <input id="pickup_date" name="pickup_date" type="date" min="{{ now()->format('Y-m-d') }}" value="{{ old('pickup_date', now()->format('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="pickup_time" class="mb-1.5 block text-sm font-semibold text-slate-700">Jam jemput</label>
                            <select id="pickup_time" name="pickup_time" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                @foreach (['08:00 - 10:00', '10:00 - 12:00', '13:00 - 15:00', '15:00 - 17:00', '17:00 - 19:00'] as $time)
                                    <option value="{{ $time }}" @selected(old('pickup_time') === $time)>{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Metode</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach (\App\Models\Order::PICKUP_METHODS as $value => $label)
                                    <label class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-center text-xs font-semibold text-slate-600 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                                        <input type="radio" name="pickup_method" value="{{ $value }}" class="sr-only" @checked(old('pickup_method', 'pickup') === $value)>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="pickup_address" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat penjemputan</label>
                            <textarea id="pickup_address" name="pickup_address" rows="2" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Nama jalan, nomor rumah, kelurahan, kota...">{{ old('pickup_address', auth()->user()->address) }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
                            <textarea id="notes" name="notes" rows="2" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: ada noda lumpur di bagian depan, mohon hati-hati">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">3</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Metode pembayaran</h2>
                            <p class="text-xs text-slate-500">Bayar saat sepatu dijemput atau transfer bank.</p>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-2 sm:grid-cols-2">
                        @foreach (\App\Models\Order::PAYMENT_METHODS as $value => $label)
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('payment_method', 'cod') === $value)>
                                <x-icon :name="$value === 'cod' ? 'money' : 'box'" class="h-4 w-4" />
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1">
                <div class="sticky top-24 rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900">Ringkasan pesanan</h2>
                    <p class="text-xs text-slate-500">Periksa kembali sebelum memesan.</p>

                    <div id="summary-empty" class="mt-5 rounded-xl border border-dashed border-slate-300 bg-slate-50/60 px-4 py-8 text-center text-sm text-slate-400">
                        Pilih layanan terlebih dahulu.
                    </div>

                    <div id="summary-content" class="mt-5 hidden">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p id="summary-name" class="text-sm font-bold text-slate-900"></p>
                            <p id="summary-seller" class="mt-0.5 text-xs text-slate-500"></p>
                            <p id="summary-days" class="mt-2 flex items-center gap-1.5 text-xs text-slate-500"></p>
                        </div>

                        <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                            <div class="flex items-center justify-between text-slate-500">
                                <span>Harga / pasang</span>
                                <span id="summary-price" class="font-semibold text-slate-700"></span>
                            </div>
                            <div class="flex items-center justify-between text-slate-500">
                                <span>Jumlah</span>
                                <span id="summary-qty" class="font-semibold text-slate-700"></span>
                            </div>
                            <div class="flex items-center justify-between text-slate-500">
                                <span>Metode</span>
                                <span id="summary-method" class="font-semibold text-slate-700">Antar Jemput</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-slate-600">Total</span>
                            <span id="summary-total" class="text-xl font-bold text-blue-700"></span>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl bg-amber-50 p-3.5 text-xs text-amber-800 ring-1 ring-amber-100">
                        <p class="font-semibold">Antar jemput gratis</p>
                        <p class="mt-0.5 text-amber-700/80">Untuk area kota dengan minimal 2 pasang sepatu.</p>
                    </div>

                    <button type="submit" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                        Buat pesanan <x-icon name="arrow-right" class="h-4 w-4" />
                    </button>
                    <p class="mt-2 text-center text-[11px] text-slate-400">Dengan memesan, Anda menyetujui ketentuan Shaos Care.</p>
                </div>
            </div>
        </form>

        <script>
            (() => {
                const form = document.getElementById('order-form');
                if (!form) return;

                const qtyInput = document.getElementById('quantity');
                const radios = form.querySelectorAll('input[name="service_id"]');
                const empty = document.getElementById('summary-empty');
                const content = document.getElementById('summary-content');
                const methodLabels = @json(\App\Models\Order::PICKUP_METHODS);

                const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

                const update = () => {
                    const selected = form.querySelector('input[name="service_id"]:checked');
                    const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

                    if (!selected) {
                        empty.classList.remove('hidden');
                        content.classList.add('hidden');
                        return;
                    }

                    const price = parseInt(selected.dataset.price || '0', 10);
                    const method = form.querySelector('input[name="pickup_method"]:checked')?.value ?? 'pickup';

                    document.getElementById('summary-name').textContent = selected.dataset.name;
                    document.getElementById('summary-seller').textContent = 'oleh ' + selected.dataset.seller;
                    document.getElementById('summary-days').innerHTML = '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> Estimasi ' + selected.dataset.days + ' hari';
                    document.getElementById('summary-price').textContent = rupiah(price);
                    document.getElementById('summary-qty').textContent = qty + ' pasang';
                    document.getElementById('summary-method').textContent = methodLabels[method] ?? method;
                    document.getElementById('summary-total').textContent = rupiah(price * qty);

                    empty.classList.add('hidden');
                    content.classList.remove('hidden');
                };

                radios.forEach((radio) => radio.addEventListener('change', update));
                qtyInput.addEventListener('input', update);
                form.querySelectorAll('input[name="pickup_method"]').forEach((radio) => radio.addEventListener('change', update));

                document.getElementById('qty-minus')?.addEventListener('click', () => {
                    qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
                    update();
                });

                document.getElementById('qty-plus')?.addEventListener('click', () => {
                    qtyInput.value = Math.min(20, parseInt(qtyInput.value || '1', 10) + 1);
                    update();
                });

                update();
            })();
        </script>
    @endif
@endsection
