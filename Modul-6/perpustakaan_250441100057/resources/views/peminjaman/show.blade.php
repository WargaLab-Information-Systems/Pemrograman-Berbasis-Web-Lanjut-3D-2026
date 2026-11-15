
<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">

            <div class="bg-white shadow rounded-lg p-6">

                <h2 class="text-xl font-bold mb-5">
                    Detail Peminjaman
                </h2>

                <p class="mb-3">
                    <strong>ID Peminjaman:</strong>
                    {{ $peminjaman->id }}
                </p>

                <p class="mb-3">
                    <strong>Nama Anggota:</strong>
                    {{ $peminjaman->anggota->nama ?? '-' }}
                </p>

                <p class="mb-3">
                    <strong>Judul Buku:</strong>
                    {{ $peminjaman->buku->judul ?? '-' }}
                </p>

                <p class="mb-3">
                    <strong>Tanggal Pinjam:</strong>
                    {{ $peminjaman->tanggal_pinjam }}
                </p>

                <p class="mb-3">
                    <strong>Tanggal Kembali:</strong>
                    {{ $peminjaman->tanggal_kembali ?? '-' }}
                </p>

                <p class="mb-3">
                    <strong>Status:</strong>
                    {{ $peminjaman->status }}
                </p>

            </div>

        </div>
    </div>

</x-app-layout>
