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
];
