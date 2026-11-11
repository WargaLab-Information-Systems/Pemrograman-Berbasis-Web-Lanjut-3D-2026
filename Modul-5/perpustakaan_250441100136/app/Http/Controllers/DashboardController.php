<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    // Menampilkan Halaman Dashboard khusus Admin.

    public function admin()
    {
        return view('admin.dashboard');
    }

    // Menampilkan Halaman Dashboard khusus Petugas.
    public function petugas()
    {
        return view('petugas.dashboard');
    }
}
