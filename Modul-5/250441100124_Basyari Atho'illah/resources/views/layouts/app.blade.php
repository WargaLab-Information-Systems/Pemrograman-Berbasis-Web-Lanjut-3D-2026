<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') &mdash; {{ config('app.name', 'Perpustakaan Digital') }} @else {{ config('app.name', 'Perpustakaan Digital') }} @endif</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Font Awesome 6 Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts & Styles via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        

        @stack('styles')
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Flash Notification Banners -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
            @if (session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-check text-base"></i>
                        </div>
                        <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <p class="text-sm font-medium text-rose-900">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Optional Header Slot -->
        @isset($header)
            <header class="bg-white border-b border-slate-200 shadow-xs">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content Area -->
        <main class="flex-1">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Sistem Perpustakaan Digital &bull; Modul 5: Autentikasi & Role-Based Access Control (RBAC)</p>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
