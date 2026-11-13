<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') | PERDIG RADIT</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f4ef;
            color: #333;
        }
        /* Header Tema Cokelat Tua */
        .header {
            background-color: #3b2f2f;
            color: white;
            padding: 30px 20px 10px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 20px 0;
            font-size: 14px;
            color: #d1c7be;
        }
        /* Navbar Menu */
        .navbar {
            background-color: #2e2424;
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 0;
            border-top: 1px solid #4a3d3d;
        }
        .navbar a {
            color: #b0a499;
            text-decoration: none;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: bold;
            transition: color 0.2s;
        }
        .navbar a:hover, .navbar a.active {
            color: white;
            border-bottom: 3px solid #d4a373;
        }
        /* Konten Utama */
        .main-content {
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        /* Style Umum Komponen */
        .hero {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            max-width: 550px;
            margin: 40px auto;
        }
        .hero h2 {
            margin-top: 0;
            color: #3b2f2f;
            font-size: 22px;
        }
        .hero p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .btn {
            display: inline-block;
            background-color: #3b2f2f;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #4f4040;
        }
        .form-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e3dbd0;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .form-card h3 {
            margin-top: 0;
            color: #3b2f2f;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 13px;
            color: #555;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            background-color: #3b2f2f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        button[type="submit"]:hover {
            background-color: #4f4040;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        th, td {
            border-bottom: 1px solid #eee;
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #3b2f2f;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Header & Navigasi Global -->
    <div class="header">
        <h1>PERDIG RADIT</h1>
        <p>Temukan buku favoritmu di sini</p>
    </div>
    <div class="navbar">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
        <a href="/buku" class="{{ request()->is('buku*') ? 'active' : '' }}">Daftar Buku</a>
    </div>

    <!-- Konten Dinamis -->
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>