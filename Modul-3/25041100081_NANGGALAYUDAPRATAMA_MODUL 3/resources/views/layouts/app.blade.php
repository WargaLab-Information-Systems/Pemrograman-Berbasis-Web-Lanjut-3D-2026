<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-slate-50 text-slate-800 flex flex-col justify-between font-sans antialiased">

    <header class="bg-blue-600 shadow-md sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-white font-bold text-lg tracking-wide">AmbaDigital Library</h1>
            <nav class="flex space-x-2">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-blue-100 hover:bg-blue-700 transition font-medium text-sm">Beranda</a>
                <a href="{{ route('buku.index') }}" class="px-4 py-2 rounded-xl text-blue-100 hover:bg-blue-700 transition font-medium text-sm">Daftar Buku</a>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl w-full mx-auto px-6 py-10 flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <p>&copy; {{ date('Y') }} ambaLibrary. All rights reserved.</p>
    </footer>

</body>
</html>