<?php
// === merkbarangindex.php ===
// Contoh data merek barang
$merekBarang = [
    ['id' => 1, 'nama' => 'Samsung'],
    ['id' => 2, 'nama' => 'IKEA'],
    ['id' => 3, 'nama' => 'LG'],
    ['id' => 4, 'nama' => 'Panasonic']
];
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merek Barang - SiBBesar</title>
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
            text-align: left;
        }
        th {
            background: var(--panel);
        }
        a.btn {
            background: var(--accent);
            color: #111;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h3>📦 SiBBesar</h3>
            <div class="nav-links">
                <a href="dashboard.php">🏠 Dashboard</a>
                <a href="jenisbarangindex.php">🧩 Jenis Barang</a>
                <a href="merkbarangindex.php" class="active">🏷️ Merek Barang</a>
                <a href="supplierbarangindex.php">🚚 Supplier Barang</a>
            </div>
        </div>
        <div style="font-size:12px;opacity:0.7;text-align:center;margin-top:20px;">
            &copy; <?= date('Y') ?> SiBBesar
        </div>
    </div>

    <div class="main">
        <h1>🏷️ Daftar Merek Barang</h1>

        <div class="card">
            <a href="#" class="btn">+ Tambah Merek</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Merek</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($merekBarang as $merek): ?>
                    <tr>
                        <td><?= $merek['id'] ?></td>
                        <td><?= htmlspecialchars($merek['nama']) ?></td>
                        <td>
                            <a href="#" class="btn" style="padding:5px 10px;font-size:13px;">✏️ Edit</a>
                            <a href="#" class="btn" style="padding:5px 10px;font-size:13px;background:#f66;color:white;">🗑️ Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
