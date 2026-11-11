<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    
    <!-- Memuat Tailwind CSS dari Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <!-- Header & Navigasi -->
    <header class="bg-[#0f172a] text-white py-8 shadow-md">
        <div class="container mx-auto px-4 text-center">
            <!-- Judul Website -->
            <h1 class="text-3xl font-bold mb-6">Perpustakaan Digital</h1>
            
            <!-- Menu Navigasi -->
            <nav class="flex justify-center space-x-8 text-sm">
                
                <!-- Link Beranda -->
                <a href="{{ route('home') }}" 
                   class="transition-colors duration-200 {{ request()->routeIs('home') ? 'text-white font-bold border-b-2 border-white pb-1' : 'text-gray-400 hover:text-white' }}">
                    Beranda
                </a>
                
                <!-- Link Daftar Buku (Aktif juga saat berada di detail buku) -->
                <a href="{{ route('buku.index') }}" 
                   class="transition-colors duration-200 {{ request()->is('buku*') ? 'text-white font-bold border-b-2 border-white pb-1' : 'text-gray-400 hover:text-white' }}">
                    Daftar Buku
                </a>

            </nav>
        </div>
    </header>

    <!-- Area Konten Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0f172a] text-gray-400 text-center py-4 text-xs mt-auto">
        <p>&copy; 2026 Perpustakaan_250441100066. All rights reserved.</p>
    </footer>

</body>
</html>