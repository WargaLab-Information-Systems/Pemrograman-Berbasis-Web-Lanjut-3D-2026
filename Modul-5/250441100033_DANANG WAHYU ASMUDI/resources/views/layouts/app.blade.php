<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perpustakaan Digital UTM') }}</title>

    <!-- Tailwind CDN agar styling selalu berfungsi tanpa build step -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col justify-between">
    <div>
        @include('layouts.navigation')

        <!-- Page Heading (Opsional) -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-6xl mx-auto py-4 px-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="max-w-6xl mx-auto px-6 py-8">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <footer class="bg-[#111827] text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2026 Perpustakaan Digital. All rights reserved.</p>
    </footer>
</body>
</html>
