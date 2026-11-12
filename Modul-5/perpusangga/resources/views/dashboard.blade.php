<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            Dashboard Siswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Selamat Datang, {{ Auth::user()->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Anda masuk sebagai Siswa AmbaPus.</p>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Hak Akses Siswa</h4>
                    <ul class="text-sm text-slate-700 space-y-2 list-disc list-inside">
                        <li>Melihat daftar dan detail buku</li>
                        <li>Melihat dan memperbarui profil sendiri</li>
                        <li>Melihat riwayat peminjaman milik sendiri</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>