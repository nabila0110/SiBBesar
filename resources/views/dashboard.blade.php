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

        .menu-section {
            margin: 20px 0;
            padding: 0 10px;
        }

        .menu-label {
            padding: 8px 20px;
            font-size: 11px;
            font-weight: 600;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background-color: #f7fafc;
            color: #4c6fff;
        }

        .menu-item.active {
            background-color: #4c6fff;
            color: white;
        }

        .menu-item span {
            margin-right: 10px;
            font-size: 16px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div style="display:flex;min-height:100vh;">
        <!-- Sidebar -->
        <aside style="width:250px;background:white;padding:20px 0;box-shadow:2px 0 5px rgba(0,0,0,0.05);overflow-y:auto;">
            <div style="padding:0 20px 20px;font-size:20px;font-weight:700;color:#4c6fff;">SiBBesar</div>
            
            <!-- Main Menu -->
            <div style="margin:20px 0;padding:0 10px;">
                <a href="#" class="menu-item active">
                    <span>📊</span> Dashboard
                </a>
                <a href="#" class="menu-item">
                    <span>🏢</span> Daftar Perusahaan
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

            <!-- Akuntansi Section -->
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

            <!-- Perusahaan Section -->
            <div class="menu-section">
                <div class="menu-label">Perusahaan</div>
                <a href="#" class="menu-item">
                    <span>📦</span> Data Barang
                </a>
            </div>

            <!-- Laporan Section -->
            <div class="menu-section">
                <div class="menu-label">Laporan</div>
                <a href="#" class="menu-item">
                    <span>💼</span> Laporan Posisi Keuangan
                </a>
                <a href="#" class="menu-item">
                    <span>💰</span> Laporan Laba Rugi
                </a>
            </div>

            <!-- Penghasilan Section -->
            <div class="menu-section">
                <div class="menu-label">Penghasilan</div>
                <a href="{{ route('pph21.index') }}" class="menu-item">
                    <span>📊</span> Pajak Penghasilan
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main style="flex:1;padding:30px;">
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
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">
                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Saldo Kas</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">150,000,000</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Lihat disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#e0e7ff;color:#4c6fff;">💵</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Hutang Usaha</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">45,000,000</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fef3c7;color:#f59e0b;">💰</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Piutang Usaha</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">30,000,000</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#d1fae5;color:#10b981;">📈</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Data Akun</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Lihat disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fed7d7;color:#f56565;">👤</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">
                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Ubah Perusahaan</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#e0e7ff;color:#4c6fff;">🏢</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Neraca</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fef3c7;color:#f59e0b;">⚖️</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Laba Rugi</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#d1fae5;color:#10b981;">💹</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Jurnal</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">125</div>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fed7d7;color:#f56565;">📝</div>
                </div>
            </div>

            <!-- Summary Table -->
            <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                    <h2 style="font-size:18px;font-weight:600;margin:0;">Activity & Summary</h2>
                    <button style="padding:8px 16px;border:1px solid #e2e8f0;background:white;border-radius:6px;cursor:pointer;font-size:13px;">This month</button>
                </div>

                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:12px;font-size:13px;color:#718096;font-weight:600;border-bottom:2px solid #e2e8f0;">Item</th>
                            <th style="text-align:left;padding:12px;font-size:13px;color:#718096;font-weight:600;border-bottom:2px solid #e2e8f0;">Value</th>
                            <th style="text-align:left;padding:12px;font-size:13px;color:#718096;font-weight:600;border-bottom:2px solid #e2e8f0;">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Jumlah Jurnal</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">125</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">-</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Saldo Kas</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">150,000,000</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Cached from accounts</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Hutang Usaha (sisa)</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">45,000,000</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Sum of payables.remaining_amount</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Piutang Usaha (sisa)</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">30,000,000</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Sum of receivables.remaining_amount</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>