<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <p>Selamat Datang, Administrator!</p>

                <!-- Pengecekan Permission di Tampilan -->
                @can('kelola pengguna')
                    <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                        Anda memiliki hak akses untuk <strong>kelola pengguna</strong>.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>