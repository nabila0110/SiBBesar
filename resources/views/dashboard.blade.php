<?php
// Koneksi Database (sesuaikan dengan konfigurasi Anda)
$host = 'localhost';
$dbname = 'sibbesar_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Ambil data statistik
$saldoKas = 40689;
$hutangUsaha = 10293;
$piutangUsaha = 89000;
$jurnal = 2040;

// Ambil data project
$projectQuery = "SELECT * FROM projects ORDER BY deadline DESC LIMIT 3";
$projects = $pdo->query($projectQuery)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiBBesar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            color: #2d3748;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .logo {
            padding: 0 20px 20px;
            font-size: 20px;
            font-weight: 700;
            color: #4c6fff;
        }

        .menu-section {
            margin: 20px 0;
        }

        .menu-label {
            padding: 0 20px;
            font-size: 11px;
            color: #a0aec0;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }

        .menu-item:hover {
            background: #f7fafc;
        }

        .menu-item.active {
            background: #4c6fff;
            color: white;
            border-radius: 8px;
            margin: 0 10px;
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9d5ff;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-info h3 {
            font-size: 13px;
            color: #718096;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-info .value {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-info .link {
            color: #4c6fff;
            font-size: 13px;
            text-decoration: none;
            margin-top: 8px;
            display: inline-block;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .icon-blue { background: #e0e7ff; color: #4c6fff; }
        .icon-yellow { background: #fef3c7; color: #f59e0b; }
        .icon-green { background: #d1fae5; color: #10b981; }
        .icon-orange { background: #fed7d7; color: #f56565; }

        /* Project Section */
        .project-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .project-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 12px;
            font-size: 13px;
            color: #718096;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f7fafc;
            font-size: 14px;
        }

        .project-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .project-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9d5ff;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fed7d7;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">SiBBesar</div>
            
            <div class="menu-section">
                <a href="#" class="menu-item active">
                    <span>📊</span> Dashboard
                </a>
                <a href="#" class="menu-item">
                    <span>📋</span> Daftar Perusahaan
                </a>
                <a href="#" class="menu-item">
                    <span>💰</span> Daftar Hutang
                </a>
                <a href="#" class="menu-item">
                    <span>💵</span> Daftar Piutang
                </a>
                <a href="#" class="menu-item">
                    <span>📦</span> Daftar Aset
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Akuntansi</div>
                <a href="#" class="menu-item">
                    <span>👤</span> Daftar Akun
                </a>
                <a href="#" class="menu-item">
                    <span>📝</span> Jurnal Umum
                </a>
                <a href="#" class="menu-item">
                    <span>📖</span> Buku Besar
                </a>
                <a href="#" class="menu-item">
                    <span>📊</span> Neraca Saldo Awal
                </a>
                <a href="#" class="menu-item">
                    <span>📉</span> Neraca Saldo Akhir
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Perusahaan</div>
                <a href="#" class="menu-item">
                    <span>📦</span> Data Barang
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Laporan</div>
                <a href="#" class="menu-item">
                    <span>💼</span> Laporan Posisi Keuangan
                </a>
                <a href="#" class="menu-item">
                    <span>💰</span> Laporan Laba Rugi
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Penghasilan</div>
                <a href="#" class="menu-item">
                    <span>📊</span> Pajak Penghasilan
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="User" class="user-avatar">
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">Moni Roy</div>
                        <div style="font-size: 12px; color: #718096;">Admin</div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Saldo Kas</h3>
                        <div class="value"><?php echo number_format($saldoKas, 0, ',', '.'); ?></div>
                        <a href="#" class="link">Lihat disini</a>
                    </div>
                    <div class="stat-icon icon-blue">👥</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Hutang Usaha</h3>
                        <div class="value"><?php echo number_format($hutangUsaha, 0, ',', '.'); ?></div>
                        <a href="#" class="link">Klik disini</a>
                    </div>
                    <div class="stat-icon icon-yellow">📦</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Piutang Usaha</h3>
                        <div class="value">$<?php echo number_format($piutangUsaha, 0, ',', '.'); ?></div>
                        <a href="#" class="link">Klik disini</a>
                    </div>
                    <div class="stat-icon icon-green">📈</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Data Akun</h3>
                        <a href="#" class="link">Lihat disini</a>
                    </div>
                    <div class="stat-icon icon-orange">🕐</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Ubah Perusahaan</h3>
                        <a href="#" class="link">Klik disini</a>
                    </div>
                    <div class="stat-icon icon-blue">👥</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Neraca</h3>
                        <a href="#" class="link">Klik disini</a>
                    </div>
                    <div class="stat-icon icon-yellow">📦</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>laba Rugi</h3>
                        <a href="#" class="link">Klik disini</a>
                    </div>
                    <div class="stat-icon icon-green">📈</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Jurnal</h3>
                        <div class="value"><?php echo $jurnal; ?></div>
                    </div>
                    <div class="stat-icon icon-orange">🕐</div>
                </div>
            </div>

            <!-- Project Details -->
            <div class="project-section">
                <div class="project-header">
                    <h2>Project Details</h2>
                    <button class="filter-btn">October ▼</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Location</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="project-name">
                                    <img src="https://via.placeholder.com/32" class="project-avatar" alt="">
                                    <span>Gedung Integrated Class</span>
                                </div>
                            </td>
                            <td>Jl. Bina Krida, Panam</td>
                            <td>12.09.2019 - 12:53 PM</td>
                            <td><span class="status-badge status-delivered">Delivered</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="project-name">
                                    <img src="https://via.placeholder.com/32" class="project-avatar" alt="">
                                    <span>Perpustakaan UNRI</span>
                                </div>
                            </td>
                            <td>Jl. Bina Krida, Panam</td>
                            <td>12.09.2019 - 12:53 PM</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="project-name">
                                    <img src="https://via.placeholder.com/32" class="project-avatar" alt="">
                                    <span>Jembatan Lelon</span>
                                </div>
                            </td>
                            <td>Jl. Perjuangan, Rumbai</td>
                            <td>12.09.2019 - 12:53 PM</td>
                            <td><span class="status-badge status-rejected">Rejected</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>