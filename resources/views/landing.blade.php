<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pilihan treatment perawatan sepatu, harga, dan informasi antar-jemput Shaos Care.">
    <title>Shaos Care — Perawatan Sepatu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/landing/styles.css">
    <noscript><style>.reveal { opacity: 1; transform: none; }</style></noscript>
</head>
<body data-whatsapp-number="{{ $whatsappAdminNumber }}">
    @if ($isPreview)
        <div style="position:fixed;z-index:100;bottom:16px;left:50%;transform:translateX(-50%);background:#002a5c;color:#fff;padding:10px 16px;border-radius:10px;font:600 13px Manrope,sans-serif;box-shadow:0 8px 24px #002a5c40">
            Pratinjau draft · <a href="{{ route('admin.website.edit') }}" style="text-decoration:underline">Kembali ke editor</a>
        </div>
    @endif
    <header class="site-header" id="site-header">
        <div class="container nav">
            <a class="brand" href="#home" aria-label="Awash Care">
                <img src="{{ $content['logo'] }}" alt="Shaos Care" class="brand__logo">
            </a>

            <nav class="nav__links" id="nav-links" aria-label="Menu utama">
                <a href="#layanan">Treatment</a>
                <a href="#harga">Harga</a>
                <a href="#antar-jemput">Antar-jemput</a>
                <a href="#cara-order">Cara Order</a>
                <a href="#faq">FAQ</a>
            </nav>

            <div class="nav__actions">
                <a class="btn btn--primary" href="{{ route('login') }}">
                    Pesan Sekarang
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </div>

            <button class="nav__toggle" id="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <main>
        <section class="hero" id="home">
            <div class="container hero__grid">
                <div class="hero__copy">
                    <p class="hero__eyebrow">{{ $content['hero_eyebrow'] }}</p>
                    <h1>{{ $content['hero_title'] }}</h1>
                    <p class="hero__lead">{{ $content['hero_description'] }}</p>

                    <div class="hero__cta">
                        <a class="btn btn--primary btn--lg" href="#" data-whatsapp-message="Halo Admin, saya ingin berkonsultasi mengenai treatment untuk sepatu saya. Saya akan mengirimkan foto sepatu. Mohon bantu cek treatment, harga, dan opsi antar-jemput." target="_blank" rel="noopener noreferrer">
                            Konsultasi Treatment
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/></svg>
                        </a>
                        <a class="btn btn--ghost btn--lg" href="#layanan">Lihat Treatment</a>
                    </div>
                    <p class="hero__proof"><span class="hero__proof-icon" aria-hidden="true">i</span> {{ $content['hero_proof'] }}</p>
                </div>

                <div class="hero__visual">
                    <figure class="hero__media">
                        <img src="{{ $content['hero_image'] }}" alt="Proses perawatan sepatu" width="720" height="900" loading="eager">
                    </figure>
                </div>
            </div>
        </section>

        <section class="strip" aria-label="Informasi layanan">
            <div class="container strip__grid">
                <div class="strip__item"><span class="strip__icon" aria-hidden="true">01</span><span>Kenali kondisi sepatu</span></div>
                <div class="strip__item"><span class="strip__icon" aria-hidden="true">02</span><span>Pilih treatment dan cek harga</span></div>
                <div class="strip__item"><span class="strip__icon" aria-hidden="true">03</span><span>Konfirmasi langsung dengan admin</span></div>
            </div>
        </section>

        <section class="section" id="layanan">
            <div class="container">
                <div class="section-head section-head--split reveal">
                    <div>
                        <p class="eyebrow">Katalog treatment</p>
                        <h2>Pilih perawatan sesuai kebutuhan.</h2>
                    </div>
                    <p class="section-head__aside">Treatment, estimasi, dan harga di bawah ini berasal dari daftar layanan yang tersedia. Untuk bahan sepatu tertentu, kirim foto agar admin dapat membantu memeriksa.</p>
                </div>

                <div class="service-grid">
                    @foreach ($treatments as $treatment)
                        <article class="service-card reveal" id="{{ $treatment['slug'] }}">
                            <div class="service-card__media">
                                <img src="/landing/assets/{{ $treatment['image'] }}" alt="{{ $treatment['image_alt'] }}" width="640" height="400" loading="lazy">
                            </div>
                            <div class="service-card__body">
                                <p class="service-card__tag">{{ $treatment['category'] }}</p>
                                <div class="service-card__title-row">
                                    <h3>{{ $treatment['name'] }}</h3>
                                </div>
                                <p class="service-card__desc">{{ $treatment['description'] }}</p>
                                <p class="treatment-label">Cocok untuk</p>
                                <ul class="service-card__list">
                                    @foreach ($treatment['problems'] as $problem)
                                        <li>{{ $problem }}</li>
                                    @endforeach
                                </ul>
                                <ul class="treatment-details">
                                    @foreach ($treatment['details'] as $detail)
                                        <li>{{ $detail }}</li>
                                    @endforeach
                                </ul>
                                <div class="treatment-price">
                                    <span>Harga per pasang</span>
                                    <strong>Rp{{ number_format($treatment['price'], 0, ',', '.') }}</strong>
                                </div>
                                <a class="service-card__link" href="#" data-whatsapp-message="Halo Admin, saya ingin konsultasi treatment {{ $treatment['name'] }}. Saya akan mengirimkan foto sepatu. Mohon konfirmasi kecocokan treatment, harga, dan estimasi pengerjaan." target="_blank" rel="noopener noreferrer">
                                    Tanya treatment ini
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section section--tint" id="panduan-treatment">
            <div class="container">
                <div class="section-head section-head--start reveal">
                    <p class="eyebrow">Panduan singkat</p>
                    <h2>Bingung Memilih Treatment? Kenali Kondisi Sepatumu!</h2>
                    <p>Pilih kondisi yang paling mendekati. Jika nodanya sulit dinilai atau bahan sepatu sensitif, konsultasikan dulu dengan admin.</p>
                </div>

                <div class="chooser-list">
                    <article class="chooser-item">
                        <div><h3>Kotor karena dipakai sehari-hari</h3><p>Fast Cleaning untuk membersihkan upper dan midsole dari kotoran ringan.</p></div>
                        <a href="#fast-cleaning">Lihat Fast Cleaning <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="chooser-item">
                        <div><h3>Noda sulit dibersihkan</h3><p>Deep Cleaning mencakup seluruh bagian sepatu. Admin dapat memeriksa foto sebelum memastikan treatment.</p></div>
                        <a href="#deep-cleaning">Lihat Deep Cleaning <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="chooser-item">
                        <div><h3>Bagian dalam kotor atau berbau</h3><p>Deep Cleaning mencakup bagian dalam dan deodorizer. Kondisi sepatu dapat dikonsultasikan lebih dulu.</p></div>
                        <a href="#deep-cleaning">Lihat Deep Cleaning <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="chooser-item">
                        <div><h3>Sol sepatu mulai menguning</h3><p>Unyellowing ditujukan untuk perawatan sol putih yang menguning.</p></div>
                        <a href="#unyellowing">Lihat Unyellowing <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="chooser-item">
                        <div><h3>Sepatu perlu dibersihkan menyeluruh</h3><p>Deep Cleaning membersihkan bagian luar dan dalam, termasuk sol dan lining.</p></div>
                        <a href="#deep-cleaning">Lihat Deep Cleaning <span aria-hidden="true">→</span></a>
                    </article>
                </div>

                <div class="chooser-help">
                    <p>Masih ragu treatment mana yang cocok?</p>
                    <a class="btn btn--primary" href="#" data-whatsapp-message="Halo Admin, saya bingung memilih treatment. Saya akan mengirimkan foto dan kondisi sepatu untuk dikonsultasikan." target="_blank" rel="noopener noreferrer">Konsultasi dengan Admin</a>
                </div>
            </div>
        </section>

        <section class="section" id="harga">
            <div class="container price-section">
                <div class="price-copy reveal">
                    <p class="eyebrow">Harga layanan</p>
                    <h2>Harga jelas sebelum Anda memesan.</h2>
                    <p>Harga treatment dihitung per pasang sesuai daftar di bawah. Biaya antar-jemput, jika berlaku, dikonfirmasi admin sebelum pesanan disepakati.</p>
                    <p>Kirim foto sepatu melalui WhatsApp untuk konsultasi kondisi dan memastikan pilihan treatment.</p>
                    <a class="btn btn--primary" href="#" data-whatsapp-message="Halo Admin, saya ingin tanya harga treatment sepatu. Saya akan mengirimkan foto sepatu dan mohon informasi biaya treatment serta antar-jemput untuk lokasi saya." target="_blank" rel="noopener noreferrer">Tanya Harga</a>
                </div>

                <div class="price-list reveal" aria-label="Daftar harga treatment">
                    @foreach ($treatments as $treatment)
                        <a class="price-row" href="#{{ $treatment['slug'] }}">
                            <span>{{ $treatment['name'] }}</span>
                            <strong>Rp{{ number_format($treatment['price'], 0, ',', '.') }}</strong>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section section--tint" id="antar-jemput">
            <div class="container pickup-section">
                <div class="pickup-copy reveal">
                    <p class="eyebrow">Pengambilan sepatu</p>
                    <h2>Layanan Antar-Jemput Sepatu</h2>
                    <p>Pilih opsi pengambilan yang sesuai. Area, jadwal, dan biaya akhir dikonfirmasi admin melalui WhatsApp sebelum pesanan disepakati.</p>
                    <a class="btn btn--primary" href="#" data-whatsapp-message="Halo Admin, saya ingin tanya layanan antar-jemput sepatu untuk lokasi saya. Mohon informasikan jangkauan area, jadwal, dan kemungkinan biaya tambahan." target="_blank" rel="noopener noreferrer">Tanya Antar-Jemput</a>
                </div>

                <div class="pickup-options reveal">
                    <article>
                        <h3>COD / bayar di tempat</h3>
                        <p>Pembayaran COD tersedia sebagai opsi. Konfirmasi ketentuan COD kepada admin sesuai lokasi Anda.</p>
                    </article>
                    <article>
                        <h3>Penjemputan ke lokasi</h3>
                        <p>Penjemputan dapat diminta. Ketersediaan layanan di wilayah utama dan jadwalnya dikonfirmasi admin.</p>
                    </article>
                    <article>
                        <h3>Di luar wilayah utama</h3>
                        <p>Antar-jemput dapat dilakukan jika memungkinkan. Biaya tambahan dapat dihitung berdasarkan jarak dalam kilometer dan dikonfirmasi sebelum Anda menyetujui.</p>
                    </article>
                    <article>
                        <h3>Antar sendiri ke lokasi usaha</h3>
                        <p>Alamat usaha belum dicantumkan. Tanyakan kepada admin apakah opsi antar sendiri tersedia dan minta alamat sebelum berangkat.</p>
                    </article>
                </div>

                <div class="pickup-policy reveal">
                    <h3>Jangkauan dan biaya antar-jemput</h3>
                    <dl>
                        @if (filled($pickupInfo['primary_area']))
                            <div><dt>Wilayah utama</dt><dd>{{ $pickupInfo['primary_area'] }}</dd></div>
                        @endif
                        @if (filled($pickupInfo['free_boundary']))
                            <div><dt>Batas wilayah atau tarif normal</dt><dd>{{ $pickupInfo['free_boundary'] }}</dd></div>
                        @endif
                        @if (filled($pickupInfo['additional_rate_per_km']))
                            <div><dt>Tarif tambahan per kilometer</dt><dd>{{ $pickupInfo['additional_rate_per_km'] }}</dd></div>
                        @endif
                        @if (filled($pickupInfo['distance_terms']))
                            <div><dt>Ketentuan perhitungan jarak</dt><dd>{{ $pickupInfo['distance_terms'] }}</dd></div>
                        @endif
                    </dl>
                    <p>Detail area utama, batas jarak, dan tarif saat ini perlu dikonfirmasi kepada admin. Tidak ada perhitungan jarak otomatis di website.</p>
                </div>
            </div>
        </section>

        <section class="section" id="cara-order">
            <div class="container">
                <div class="section-head section-head--row reveal">
                    <div>
                        <p class="eyebrow">Pemesanan melalui WhatsApp</p>
                        <h2>Cara Order</h2>
                        <p>Semua konsultasi, konfirmasi, dan kesepakatan pesanan dilakukan langsung dengan admin.</p>
                    </div>
                    <a class="btn btn--primary" href="#" data-whatsapp-message="Halo Admin, saya ingin memesan layanan perawatan sepatu. Saya akan mengirimkan foto sepatu. Mohon bantu cek treatment, harga, dan opsi antar-jemput untuk lokasi saya." target="_blank" rel="noopener noreferrer">Mulai lewat WhatsApp</a>
                </div>

                <ol class="order-steps">
                    <li><span>01</span><div><h3>Pilih treatment</h3><p>Gunakan panduan kondisi sepatu atau minta saran admin.</p></div></li>
                    <li><span>02</span><div><h3>Periksa harga</h3><p>Cek harga treatment per pasang di daftar harga.</p></div></li>
                    <li><span>03</span><div><h3>Pilih cara pengambilan</h3><p>Tanyakan opsi antar sendiri atau COD dan antar-jemput sesuai area.</p></div></li>
                    <li><span>04</span><div><h3>Hubungi admin di WhatsApp</h3><p>Klik tombol pesan dan sampaikan treatment yang diminati.</p></div></li>
                    <li><span>05</span><div><h3>Kirim informasi dan foto sepatu</h3><p>Foto membantu admin melihat kondisi sepatu sebelum memberi saran.</p></div></li>
                    <li><span>06</span><div><h3>Konfirmasi detail</h3><p>Admin membantu memastikan treatment, harga, lokasi, dan jadwal.</p></div></li>
                    <li><span>07</span><div><h3>Lanjutkan setelah sepakat</h3><p>Pesanan diproses setelah detailnya disepakati melalui WhatsApp.</p></div></li>
                </ol>
            </div>
        </section>

        <section class="section section--tint" id="faq">
            <div class="container faq">
                <div class="faq__copy reveal">
                    <p class="eyebrow">Tanya jawab</p>
                    <h2>Masih ada yang ingin ditanyakan?</h2>
                    <p>Tanyakan langsung kepada admin jika kondisi sepatu atau layanan antar-jemput Anda belum tercakup di sini.</p>
                    <a class="text-link" href="#" data-whatsapp-message="Halo Admin, saya masih memiliki pertanyaan mengenai layanan perawatan sepatu." target="_blank" rel="noopener noreferrer">Tanya lewat WhatsApp <span aria-hidden="true">→</span></a>
                </div>

                <div class="accordion reveal" data-accordion>
                    <div class="accordion__item is-open">
                        <button class="accordion__trigger" type="button" aria-expanded="true" aria-controls="faq-panel-1">Apa perbedaan Fast Cleaning dan Deep Cleaning?<span class="accordion__icon" aria-hidden="true"></span></button>
                        <div class="accordion__panel" id="faq-panel-1"><p>Fast Cleaning fokus pada upper dan midsole. Deep Cleaning mencakup pembersihan menyeluruh, termasuk bagian dalam, sol, dan lining.</p></div>
                    </div>
                    <div class="accordion__item">
                        <button class="accordion__trigger" type="button" aria-expanded="false" aria-controls="faq-panel-2">Bagaimana memilih treatment untuk noda membandel?<span class="accordion__icon" aria-hidden="true"></span></button>
                        <div class="accordion__panel" id="faq-panel-2"><p>Kirim foto kondisi sepatu melalui WhatsApp. Admin dapat membantu memeriksa apakah Deep Cleaning sesuai sebelum Anda memesan.</p></div>
                    </div>
                    <div class="accordion__item">
                        <button class="accordion__trigger" type="button" aria-expanded="false" aria-controls="faq-panel-3">Apakah harga sudah termasuk antar-jemput?<span class="accordion__icon" aria-hidden="true"></span></button>
                        <div class="accordion__panel" id="faq-panel-3"><p>Harga yang tercantum adalah harga treatment per pasang. Biaya antar-jemput, jika berlaku, diinformasikan admin sebelum pesanan disepakati.</p></div>
                    </div>
                    <div class="accordion__item">
                        <button class="accordion__trigger" type="button" aria-expanded="false" aria-controls="faq-panel-4">Bagaimana mengetahui apakah lokasi saya terjangkau?<span class="accordion__icon" aria-hidden="true"></span></button>
                        <div class="accordion__panel" id="faq-panel-4"><p>Kirim lokasi atau alamat perkiraan kepada admin. Jangkauan dan kemungkinan biaya tambahan akan dikonfirmasi sebelum Anda menyetujui layanan.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-banner">
            <div class="container cta-banner__inner">
                <div><p class="cta-eyebrow">Mulai dengan konsultasi</p><h2>{{ $content['cta_title'] }}</h2></div>
                <a class="btn btn--light btn--lg" href="{{ route('login') }}">Pesan Sekarang</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer__grid">
            <div class="footer__brand">
                <a class="brand" href="#home" aria-label="Shaos Care"><img src="{{ $content['logo'] }}" alt="Shaos Care" class="brand__logo brand__logo--footer"></a>
                <p>{{ $content['footer_description'] }}</p>
            </div>
            <div class="footer__col"><h4>Treatment</h4><a href="#fast-cleaning">Fast Cleaning</a><a href="#deep-cleaning">Deep Cleaning</a><a href="#unyellowing">Unyellowing</a></div>
            <div class="footer__col"><h4>Informasi</h4><a href="#harga">Harga</a><a href="#antar-jemput">Antar-jemput</a><a href="#cara-order">Cara Order</a><a href="#faq">FAQ</a></div>
            <div class="footer__col"><h4>Kontak</h4><a href="#" data-whatsapp-message="Halo Admin, saya ingin bertanya mengenai layanan perawatan sepatu." target="_blank" rel="noopener noreferrer">{{ $content['contact_label'] }}</a><span>{{ $content['contact_note'] }}</span></div>
        </div>
        <div class="container footer__bottom"><span>© <span id="year">2026</span> Shaos Care.</span><a href="#home">Kembali ke atas ↑</a></div>
    </footer>

    <script src="/landing/script.js"></script>
</body>
</html>
