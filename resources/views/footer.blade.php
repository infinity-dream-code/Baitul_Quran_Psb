<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pmb-green': {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
     <footer class="bg-pmb-green-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-12">
               <div class=" text-white rounded-2xl p-8 shadow-lg">
    <h3 class="text-4xl font-extrabold text-yellow-400 mb-1 tracking-wide">PSB {{ config('client.name') }}</h3>
    <p class="text-gray-300 text-base mb-5">{{ config('client.region') }}</p>
    <p class="text-gray-300 text-lg leading-relaxed mb-6">
        Sistem PSB (Penerimaan Santri Baru) adalah platform digital yang memudahkan calon santri untuk melakukan pendaftaran dan memantau status pendaftaran hingga kelulusan secara online.
    </p>
    <ul class="space-y-4 text-gray-200 text-base">
        <li class="flex items-start">
            <span class="text-yellow-400 font-semibold mr-2">1.</span>
            <div>
                <span class="font-semibold text-white">Terintegrasi dengan Payment Gateway Bank</span><br>
                Santri yang mendaftar langsung mendapatkan nominal pembayaran secara online dan dapat melakukan transaksi melalui nomor VA di berbagai channel bank.
            </div>
        </li>
        <li class="flex items-start">
            <span class="text-yellow-400 font-semibold mr-2">2.</span>
            <div>
                <span class="font-semibold text-white">Kemudahan Rekonsiliasi dan Identifikasi Pembayaran</span><br>
                Sekolah dapat dengan mudah memantau seluruh proses pendaftaran, termasuk jumlah registrasi, pembayaran, seleksi, dan kelulusan ujian masuk.
            </div>
        </li>
    </ul>
</div>

                <div>
                    <h4 class="text-2xl font-bold text-yellow-400 mb-6">SOCIAL MEDIA :</h4>
                    <div class="space-y-4">
                        @php $social = config('client.social', []); @endphp

                        <a href="{{ $social['facebook']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['facebook']['label'] ?? 'Facebook' }}</span>
                        </a>

                        <a href="{{ $social['instagram']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['instagram']['label'] ?? 'Instagram' }}</span>
                        </a>

                        <a href="{{ $social['tiktok']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .56.04.82.12V9.01a6.27 6.27 0 00-.82-.05 6.33 6.33 0 00-6.33 6.33 6.33 6.33 0 006.33 6.33 6.33 6.33 0 006.33-6.33V8.77a8.18 8.18 0 004.77 1.52V6.79a4.85 4.85 0 01-1-.1z"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['tiktok']['label'] ?? 'TikTok' }}</span>
                        </a>

                        <a href="{{ $social['x']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['x']['label'] ?? 'X' }}</span>
                        </a>

                        <a href="{{ $social['youtube']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['youtube']['label'] ?? 'YouTube' }}</span>
                        </a>

                        <a href="{{ $social['website']['url'] ?? '#' }}" target="_blank" rel="noopener" class="flex items-center space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                            <div class="w-12 h-12 bg-pmb-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-lg">{{ $social['website']['label'] ?? 'Website' }}</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-2xl font-bold text-yellow-400 mb-6">KONTAK KAMI :</h4>
                    <div class="space-y-4">
                        @foreach (config('client.whatsapp', []) as $wa)
                            @php
                                $waText = rawurlencode('Halo PSB ' . config('client.name') . ', saya ingin bertanya tentang pendaftaran santri baru.');
                            @endphp
                            <a href="https://wa.me/{{ $wa['phone'] }}?text={{ $waText }}"
                               target="_blank" rel="noopener"
                               class="flex items-start space-x-4 hover:text-yellow-400 transition-colors no-underline text-white">
                                <div class="w-12 h-12 bg-pmb-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold">{{ $wa['display'] }}</div>
                                    <div class="text-sm text-gray-300">{{ $wa['name'] }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-pmb-green-700 mt-12 pt-8">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-400">
                <p class="text-lg">© 2025 Copyright: <a href="{{url('/')}}" class="no-underline text-yellow-400 hover:underline font-semibold">PSB {{ config('client.name') }}</a></p>
            </div>
        </div>
    </footer>

</body>
</html>