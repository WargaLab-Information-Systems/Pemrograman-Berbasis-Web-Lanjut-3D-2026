<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AmbaPus') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 bg-slate-50 antialiased min-h-screen flex flex-col justify-center items-center p-4">
        
        <!-- Branding Logo -->
        <div class="mb-6 text-center">
            <a href="/" class="inline-flex items-center gap-2.5 font-bold text-slate-900 text-2xl tracking-tight">
                <span>Amba<span class="text-blue-600">Pus</span></span>
            </a>
        </div>

        <!-- Minimalist Form Card -->
        <div class="w-full sm:max-w-md bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 sm:p-8">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} AmbaPus - Perpustakaan Digital
        </div>
    </body>
</html>