<x-app-layout>
    <div class="bg-white p-8 rounded-lg shadow-md text-center max-w-3xl mx-auto my-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-3">Selamat Datang di Perpustakaan Digital UTM</h1>
        <p class="text-gray-600 mb-6">Temukan dan baca berbagai koleksi buku terbaik kami secara online.</p>
        <div class="space-x-3">
            <a href="{{ route('buku.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded shadow inline-block transition">
                Lihat Daftar Buku
            </a>
            @guest
                <a href="{{ route('login') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-5 py-2.5 rounded shadow inline-block transition">
                    Masuk ke Akun
                </a>
            @endguest
        </div>
    </div>
</x-app-layout>
