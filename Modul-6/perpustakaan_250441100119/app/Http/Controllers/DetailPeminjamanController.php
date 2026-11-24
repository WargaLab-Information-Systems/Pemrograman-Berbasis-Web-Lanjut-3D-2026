<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Gate;

class DetailPeminjamanController extends Controller
{
    public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('view', $peminjaman);

        $peminjaman->load(['anggota', 'buku']);

        return view('peminjaman.show', compact('peminjaman'));
    }
}