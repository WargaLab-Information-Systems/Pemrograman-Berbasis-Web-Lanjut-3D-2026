<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perpustakaan Digital — Sistem informasi koleksi buku digital yang modern dan mudah digunakan.">

    <title>
        @yield('title', 'Perpustakaan Digital')
    </title>

    {{-- Font Awesome Icons --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    {{-- CSS via Vite --}}
    @vite(['resources/css/app.css'])
</head>

<body>

    {{-- ==================== HEADER ==================== --}}
    <header class="site-header">
        <div class="container-main">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">

                <a href="{{ route('home') }}" class="site-header__logo">
                    <div class="site-header__icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <h1 class="site-header__title">Perpustakaan Digital</h1>
                        <p class="site-header__subtitle">Sistem Informasi Perpustakaan</p>
                    </div>
                </a>

                <div style="font-size:0.8rem; color:#94a3b8; display:none;" class="site-header__tagline">
                    <i class="fa-solid fa-book-bookmark" style="margin-right:0.375rem;"></i>
                    Koleksi Buku Digital
                </div>

            </div>
        </div>
    </header>


    {{-- ==================== NAVBAR ==================== --}}
    <nav class="site-nav">
        <div class="container-main">
            <div class="site-nav__inner">

                <div class="site-nav__links">

                    {{-- Beranda --}}
                    <a
                        id="nav-beranda"
                        href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-house"></i>
                        Beranda
                    </a>

                    {{-- Daftar Buku --}}
                    <a
                        id="nav-buku"
                        href="{{ route('buku.index') }}"
                        class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-book"></i>
                        Daftar Buku
                    </a>

                </div>

                <span style="font-size:0.8rem; color:#94a3b8;">
                    Koleksi Buku Digital
                </span>

            </div>
        </div>
    </nav>


    {{-- ==================== KONTEN UTAMA ==================== --}}
    <main class="site-main">
        @yield('content')
    </main>


    {{-- ==================== FOOTER ==================== --}}
    <footer class="site-footer">
        <div class="container-main">

            <p class="site-footer__copy">
                &copy; {{ date('Y') }} Perpustakaan Digital. Semua hak dilindungi.
            </p>

            <p class="site-footer__tech">
                Dibangun menggunakan Laravel 13 &amp; Vite
            </p>

        </div>
    </footer>

</body>
</html>