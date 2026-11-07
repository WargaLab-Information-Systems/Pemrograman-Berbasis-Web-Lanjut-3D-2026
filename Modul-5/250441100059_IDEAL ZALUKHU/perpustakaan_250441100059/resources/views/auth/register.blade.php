<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #ffffff; 
            min-height: 100vh;   
        }
        .auth-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 25px;
        }

        .auth-card {
            width: 100%;
            max-width: 470px;
            padding: 26px 42px;
            background: #fff;
            border: 1px solid #f1f1f1;
            border-radius: 0;
            box-shadow: 0 15px 45px rgba(127, 29, 29, .12);
        }

        .auth-header h1 {
            margin: 0 0 6px;
            color: #991b32;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
        }

        .auth-header p {
            margin: 0 0 24px;
            color: #888;
            font-size: 17px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #444;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 0;
            font-size: 14px;
            outline: none;
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }

        .form-control:focus {
            border-color: #b91c35;
            box-shadow: 0 0 0 3px rgba(185, 28, 53, .09);
        }

        .auth-button {
            width: 100%;
            padding: 12px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: linear-gradient(90deg, #b91c35, #8f142c);
            border: 0;
            border-radius: 0;
            transition: filter .35s ease, box-shadow .35s ease, transform .35s ease;
        }

        .auth-button:hover {
            filter: brightness(.88);
            box-shadow: 0 6px 14px rgba(127, 16, 40, .2);
            transform: translateY(-1px);
        }

        .error-message {
            margin-top: 7px;
            color: #dc2626;
            font-size: 13px;
        }

        .error-alert {
            padding: 12px;
            margin-bottom: 20px;
            color: #be123c;
            font-size: 13px;
            background: #fff1f2;
            border-radius: 9px;
        }

        .auth-footer {
            margin-top: 22px;
            color: #777;
            font-size: 13px;
            text-align: center;
        }

        .auth-link {
            color: #a71932;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 35px 25px;
            }

            .auth-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Daftar Akun Baru</h1>
                <p>Lengkapi data Anda</p>
            </div>
            @if ($errors->any())
                <div class="error-alert">Periksa kembali data yang Anda masukkan.</div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input
                        id="name"
                        class="form-control"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama"
                        required
                        autofocus
                        autocomplete="name"
                    >

                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        class="form-control"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        required
                        autocomplete="username"
                    >

                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input
                        id="password"
                        class="form-control"
                        type="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        required
                        autocomplete="new-password"
                    >

                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input
                        id="password_confirmation"
                        class="form-control"
                        type="password"
                        name="password_confirmation"
                        placeholder="Ulangi kata sandi"
                        required
                        autocomplete="new-password"
                    >

                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-button">Daftar Sekarang</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="auth-link">Masuk</a>
            </div>
        </div>
    </div>
</body>
</html>