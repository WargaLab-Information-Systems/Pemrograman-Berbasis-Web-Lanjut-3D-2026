<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perpustakaan Digital UTM') }}</title>

        <!-- Tailwind CDN agar styling form login & register langsung bekerja -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex flex-col justify-between">
        <header class="bg-[#111827] text-white shadow-md">
            <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
                <a href="/" class="text-2xl font-bold hover:text-gray-200">Perpustakaan Digital UTM</a>
                <nav class="space-x-4 text-sm text-gray-300">
                    <a href="/" class="hover:text-white">Beranda</a>
                    <a href="/buku" class="hover:text-white">Daftar Buku</a>
                </nav>
            </div>
        </header>

        <div class="flex flex-col sm:justify-center items-center py-6 px-4">
            <div class="w-full sm:max-w-md bg-white shadow-md overflow-hidden sm:rounded-lg p-6">
                {{ $slot }}
            </div>
        </div>

        <footer class="bg-[#111827] text-white text-center py-4 mt-8">
            <p class="text-sm">&copy; 2026 Perpustakaan Digital. All rights reserved.</p>
        </footer>
    </body>
</html>
