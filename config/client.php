<?php

/*
| Identitas client yang tampil di halaman publik, dashboard, dan cetakan.
| Sengaja terpisah dari APP_NAME: APP_NAME dipakai untuk nama cookie login,
| jadi mengubahnya akan me-logout semua user.
*/
return [
    'name' => env('CLIENT_NAME', 'Ponpes Baitul Qur\'an'),
    'city' => env('CLIENT_CITY', 'Pekanbaru'),
    'region' => env('CLIENT_REGION', 'Kota Pekanbaru, Prov. Riau'),
    'logo' => env('CLIENT_LOGO', 'logo_baitul_quran.jpeg'),

    // Nomor VA = prefix + nomor pendaftaran (10 digit) = 16 digit.
    'va_prefix' => env('CLIENT_VA_PREFIX', '777724'),

    'visi' => 'Mewujudkan generasi Qur\'ani yang unggul, menguasai ilmu pengetahuan, teguh dalam nilai-nilai, dan berkontribusi di tengah masyarakat.',

    'misi' => [
        'Menyelenggarakan Pendidikan Qur\'an bermutu yang memadukan khazanah klasik dan modernitas melalui metode pembelajaran yang efektif serta adaptif.',
        'Membentuk kepribadian peserta didik yang mandiri, percaya diri, dan berjiwa kepemimpinan yang berwawasan kebangsaan.',
        'Mencapai prestasi ilmiah dan spiritual yang berkelanjutan guna berkontribusi nyata bagi agama, masyarakat dan ibu pertiwi.',
    ],

    'social' => [
        'facebook' => [
            'label' => 'Ponpes Baitul Quran-Riau',
            'url' => 'https://www.facebook.com/search/top?q=Ponpes%20Baitul%20Quran-Riau',
        ],
        'instagram' => [
            'label' => '@bqriau.id',
            'url' => 'https://www.instagram.com/bqriau.id',
        ],
        'tiktok' => [
            'label' => '@bqriau.id',
            'url' => 'https://www.tiktok.com/@bqriau.id',
        ],
        'x' => [
            'label' => '@bqriau.id',
            'url' => 'https://x.com/bqriau.id',
        ],
        'youtube' => [
            'label' => 'BQRTV',
            'url' => 'https://www.youtube.com/@BQRTV',
        ],
        'website' => [
            'label' => 'www.baitulquranriau.id',
            'url' => 'https://www.baitulquranriau.id',
        ],
    ],

    'whatsapp' => [
        [
            'name' => 'Ust. Mustaghfirin, S.Pd., M.Pd',
            'phone' => '6282197349487',
            'display' => '+62 821-9734-9487',
        ],
        [
            'name' => 'Ustd. Halimah Tussa\'diyah, S.Ag',
            'phone' => '6285225610276',
            'display' => '+62 852-2561-0276',
        ],
    ],
];
