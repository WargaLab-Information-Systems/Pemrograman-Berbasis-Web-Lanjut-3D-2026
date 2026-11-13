<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perpustakaan') }} — Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Full-page gradient background -->
        <div class="min-h-screen flex"
             style="background: linear-gradient(135deg, #3730a3 0%, #4F46E5 40%, #2563EB 70%, #0891b2 100%);">

            <!-- Left decorative panel (hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center p-12 relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

                <!-- Branding content -->
                <div class="relative z-10 text-center">
                    <div class="text-8xl mb-6 drop-shadow-lg">📚</div>
                    <h1 class="text-4xl font-bold text-white mb-3 tracking-tight">Perpustakaan</h1>
                    <p class="text-indigo-200 text-lg max-w-sm leading-relaxed">
                        Sistem Manajemen Perpustakaan Digital yang modern dan mudah digunakan.
                    </p>

                    <div class="mt-10 flex flex-col gap-4 text-left">
                        <div class="flex items-center gap-3 text-indigo-100">
                            <span class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm">📖</span>
                            <span class="text-sm">Ribuan koleksi buku tersedia</span>
                        </div>
                        <div class="flex items-center gap-3 text-indigo-100">
                            <span class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm">✅</span>
                            <span class="text-sm">Peminjaman mudah dan cepat</span>
                        </div>
                        <div class="flex items-center gap-3 text-indigo-100">
                            <span class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm">📊</span>
                            <span class="text-sm">Laporan real-time terintegrasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right form panel -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12">
                <!-- Mobile branding -->
                <div class="lg:hidden text-center mb-8">
                    <span class="text-5xl">📚</span>
                    <h1 class="text-2xl font-bold text-white mt-2">Perpustakaan</h1>
                </div>

                <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 sm:p-10">
                    {{ $slot }}
                </div>

                <p class="text-indigo-200 text-xs mt-6">
                    &copy; {{ date('Y') }} Perpustakaan Digital. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
