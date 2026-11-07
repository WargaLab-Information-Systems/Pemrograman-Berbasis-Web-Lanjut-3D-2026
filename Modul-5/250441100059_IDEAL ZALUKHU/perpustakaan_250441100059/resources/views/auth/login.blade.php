
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>

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

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: white;
            padding: 32px 40px;
            border-radius: 0;
            border: 1px solid #f1f1f1;
            box-shadow: 0 15px 45px rgba(127, 29, 29, 0.12);
        }

        .login-header h1 {
            text-align: center;
            color: #991b32;
            font-size: 25px;
            font-weight: bold;
            margin: 0 0 6px;
        }

        .login-header p {
            text-align: center;
            color: #888;
            font-size: 17px;
            margin: 0 0 24px;
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
            border: 1px solid #e5e5e5;
            border-radius: 0;
            font-size: 14px;
            outline: none;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #b91c35;
            box-shadow: 0 0 0 3px rgba(185, 28, 53, 0.09);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 4px 0 20px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #555;
        }

        .remember input {
            accent-color: #a71932;
        }

        .forgot-link {
            color: #a71932;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 0;
            background: linear-gradient(90deg, #b91c35, #8f142c);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: filter 0.35s ease, box-shadow 0.35s ease, transform 0.35s ease;
        }

        .login-button:hover {
            filter: brightness(0.88);
            box-shadow: 0 6px 14px rgba(127, 16, 40, 0.2);
            transform: translateY(-1px);
        }

        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 7px;
        }

        .error-alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 9px;
            background: #fff1f2;
            color: #be123c;
            font-size: 13px;
        }

        .session-message {
            padding: 12px;
            margin-bottom: 20px;
            background: #f0fdf4;
            color: #166534;
            border-radius: 9px;
            font-size: 13px;
        }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            color: #999;
            font-size: 12px;
            line-height: 1.7;
        }

        .register-link {
            display: inline-block;
            margin-top: 18px;
            color: #a71932;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 35px 25px;
            }

            .login-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <!-- HEADER -->
        <div class="login-header">

            <h1>Perpustakaan Digital</h1>

            <p>Masuk Ke Akun</p>
        </div>

        <!-- PESAN STATUS -->
        @if (session('status'))
            <div class="session-message">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-alert">
                Email atau password salah. Silakan periksa kembali.
            </div>
        @endif

        <!-- FORM LOGIN -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                    autofocus
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
                    autocomplete="current-password"
                >

                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-options">

                <label class="remember">
                    <input type="checkbox" name="remember">
                    Ingat Saya
                </label>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="forgot-link"
                    >
                        Lupa Password?
                    </a>
                @endif

            </div>

            <button type="submit" class="login-button">
                Masuk
            </button>

        </form>

        <div style="text-align: center;">
            <span style="color: #777; font-size: 13px;">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="register-link">
                Daftar
            </a>
        </div>

    </div>

</div>

</body>
</html>