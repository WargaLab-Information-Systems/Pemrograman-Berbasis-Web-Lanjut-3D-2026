@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="panel">
                <!-- Header Panel Login -->
                <div class="panel-header text-center py-3">
                    <span class="badge border bg-light text-secondary fw-normal px-2 py-1 mb-2" style="font-size:0.75rem;">
                        Autentikasi Pengguna
                    </span>
                    <h5 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">Masuk ke Akun</h5>
                    <p class="text-muted small mb-0">Masukkan email dan kata sandi akun Anda.</p>
                </div>

                <div class="panel-body p-4">
                    <!-- Status Sesi (Misal: Password Berhasil Direset) -->
                    @if (session('status'))
                        <div class="alert border-0 rounded-2 py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="background:#f0fdf4; border:1px solid #bbf7d0 !important; color:#166534; font-size:0.8125rem;">
                            <div>{{ session('status') }}</div>
                            <button type="button" class="btn-close shadow-none" style="font-size:0.65rem;" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <!-- Alamat Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="nama@perpustakaan.test" 
                                required 
                                autofocus 
                                autocomplete="username"
                            >
                            @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kata Sandi -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label mb-0">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none" style="font-size:0.75rem;">
                                        Lupa sandi?
                                    </a>
                                @endif
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control mt-1 @error('password') is-invalid @enderror" 
                                placeholder="••••••••" 
                                required 
                                autocomplete="current-password"
                            >
                            @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ingat Sesi (Remember Me) -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label text-muted small" for="remember_me">Ingat sesi saya</label>
                        </div>

                        <!-- Tombol Masuk -->
                        <button type="submit" class="btn-brand w-100 py-2 fw-semibold">
                            Masuk ke Sistem
                        </button>
                    </form>

                    <!-- Tombol Register Akun Baru -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="text-muted small mb-2">Belum memiliki akun perpustakaan?</p>
                        <a href="{{ route('register') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2 fw-medium">
                            Register Akun Baru →
                        </a>
                    </div>
                </div>
            </div>

</div>
@endsection