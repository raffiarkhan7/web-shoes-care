<?php

return [
    'content' => [
        'hero_eyebrow' => 'Perawatan sesuai kondisi sepatu',
        'hero_title' => 'Rawat sepatu favoritmu dengan treatment yang tepat.',
        'hero_description' => 'Kenali pilihan perawatan, cek harga, lalu konsultasikan kondisi sepatu langsung dengan admin melalui WhatsApp.',
        'hero_proof' => 'Harga treatment ditampilkan transparan; kirim foto untuk konsultasi.',
        'cta_title' => 'Kirim foto sepatu dan tanyakan treatment yang sesuai.',
        'footer_description' => 'Informasi treatment, harga, dan antar-jemput perawatan sepatu.',
        'contact_label' => 'WhatsApp Admin',
        'contact_note' => 'Area dan alamat dikonfirmasi melalui WhatsApp.',
        'whatsapp_number' => null,
        'logo' => '/landing/assets/logo.png',
        'hero_image' => '/landing/assets/hero.jpg',
    ],

    'treatments' => [
        [
            'slug' => 'fast-cleaning',
            'category' => 'Pembersihan rutin',
            'name' => 'Fast Cleaning',
            'description' => 'Pembersihan bagian upper dan midsole untuk sepatu yang kotor karena pemakaian sehari-hari.',
            'problems' => ['Kotoran harian', 'Noda ringan pada upper atau midsole'],
            'details' => ['Upper dan midsole', 'Estimasi sekitar 1 hari'],
            'price' => 20000,
            'image' => 'service-fast.jpg',
            'image_alt' => 'Sepatu yang dirawat dengan Fast Cleaning',
        ],
        [
            'slug' => 'deep-cleaning',
            'category' => 'Perawatan menyeluruh',
            'name' => 'Deep Cleaning',
            'description' => 'Pembersihan menyeluruh pada bagian luar dan dalam sepatu, termasuk sol dan lining.',
            'problems' => ['Noda membandel yang perlu diperiksa', 'Bagian dalam kotor atau berbau', 'Sepatu perlu dibersihkan menyeluruh'],
            'details' => ['Seluruh bagian sepatu', 'Anti bakteri dan deodorizer', 'Estimasi sekitar 3 hari'],
            'price' => 35000,
            'image' => 'service-deep.jpg',
            'image_alt' => 'Sepatu yang dirawat dengan Deep Cleaning',
        ],
        [
            'slug' => 'unyellowing',
            'category' => 'Perawatan sol',
            'name' => 'Unyellowing',
            'description' => 'Treatment untuk membantu mengembalikan warna putih pada sol yang menguning karena oksidasi.',
            'problems' => ['Sol sepatu putih mulai menguning'],
            'details' => ['Fokus pada sol yang menguning', 'Estimasi sekitar 4 hari'],
            'price' => 55000,
            'image' => 'service-unyellow.jpg',
            'image_alt' => 'Sol sepatu yang menjalani treatment Unyellowing',
        ],
    ],

    'pickup' => [
        'primary_area' => env('PICKUP_PRIMARY_AREA'),
        'free_boundary' => env('PICKUP_FREE_BOUNDARY'),
        'additional_rate_per_km' => env('PICKUP_ADDITIONAL_RATE_PER_KM'),
        'distance_terms' => env('PICKUP_DISTANCE_TERMS'),
    ],
];
