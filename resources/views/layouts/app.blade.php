<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SiBBesar')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg1: #8e66c7;
            --bg2: #b06ab3;
            --panel: rgba(255,255,255,0.12);
            --panel2: rgba(255,255,255,0.06);
            --accent: #7b76a6;
            --white: #ffffff;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg1), var(--bg2));
            color: var(--white);
            display: flex;
        }

        .sidebar {
            width: 240px;
            background: var(--panel);
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar h3 {
            color: var(--white);
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .nav-links a {
            display: block;
            color: var(--white);
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--accent);
            color: #111;
        }

        .main {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .card {
            background: var(--panel2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: var(--white);
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        th { background: var(--panel); }

        a.btn {
            background: var(--accent);
            color: #111;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div>
            <h3>📦 SiBBesar</h3>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
                <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Persediaan</div>
                <a href="{{ route('jenis_barang.index') }}" class="{{ request()->is('jenis_barang*') ? 'active' : '' }}">🧩 Jenis Barang</a>
                <a href="{{ route('merk_barang.index') }}" class="{{ request()->is('merk_barang*') ? 'active' : '' }}">🏷️ Merek Barang</a>
                <a href="{{ route('supplier.index') }}" class="{{ request()->is('supplier*') ? 'active' : '' }}">🚚 Supplier Barang</a>
            </div>
        </div>
        <div style="font-size:12px;opacity:0.7;text-align:center;margin-top:20px;">
            &copy; {{ date('Y') }} SiBBesar
        </div>
    </div>

    <div class="main">
        @yield('content')
    </div>
</body>
</html>
