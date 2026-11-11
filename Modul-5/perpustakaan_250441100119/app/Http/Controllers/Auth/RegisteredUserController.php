<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi pengguna baru.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Memvalidasi data registrasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        // Menyimpan pengguna baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Setiap pengguna yang registrasi otomatis menjadi Siswa
        // Role Admin dan Petugas tidak dapat dipilih sendiri
        $user->assignRole('siswa');

        // Menjalankan event registrasi Laravel Breeze
        event(new Registered($user));

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);

        // Mengarahkan pengguna ke dashboard
        return redirect(route('dashboard', absolute: false));
    }
}