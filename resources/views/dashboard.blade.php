@php
    // Controller should pass: $cashBalance, $hutang, $piutang, $journalCount
    $cashBalance = $cashBalance ?? 0;
    $hutangUsaha = $hutang ?? 0;
    $piutangUsaha = $piutang ?? 0;
    $jurnal = $journalCount ?? 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiBBesar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Inline styles removed from style block; styles applied inline on elements -->
</head>
<body>
    <div style="display:flex;min-height:100vh;font-family: 'Inter', sans-serif;background-color:#f5f7fa;color:#2d3748;">
        <!-- Sidebar -->
        <aside style="width:250px;background:white;padding:20px 0;box-shadow:2px 0 5px rgba(0,0,0,0.05);">
            <div style="padding:0 20px 20px;font-size:20px;font-weight:700;color:#4c6fff;">SiBBesar</div>
            
            <div style="margin:20px 0;padding:0 10px;">
                <a href="#" style="display:block;padding:12px 20px;color:#fff;background:#4c6fff;border-radius:8px;margin:0 10px;text-decoration:none;">📊 Dashboard</a>
                <a href="#" style="display:block;padding:12px 20px;color:#4a5568;text-decoration:none;">📋 Daftar Perusahaan</a>
                <a href="#" style="display:block;padding:12px 20px;color:#4a5568;text-decoration:none;">💰 Daftar Hutang</a>
                <a href="#" style="display:block;padding:12px 20px;color:#4a5568;text-decoration:none;">💵 Daftar Piutang</a>
                <a href="#" style="display:block;padding:12px 20px;color:#4a5568;text-decoration:none;">📦 Daftar Aset</a>
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
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">{{ number_format($cashBalance, 0, ',', '.') }}</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Lihat disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#e0e7ff;color:#4c6fff;">👥</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Hutang Usaha</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">{{ number_format($hutangUsaha, 0, ',', '.') }}</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fef3c7;color:#f59e0b;">📦</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Piutang Usaha</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">{{ number_format($piutangUsaha, 0, ',', '.') }}</div>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#d1fae5;color:#10b981;">📈</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Data Akun</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Lihat disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fed7d7;color:#f56565;">🕐</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">
                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Ubah Perusahaan</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#e0e7ff;color:#4c6fff;">👥</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Neraca</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fef3c7;color:#f59e0b;">📦</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Laba Rugi</h3>
                        <a href="#" style="color:#4c6fff;font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Klik disini</a>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#d1fae5;color:#10b981;">📈</div>
                </div>

                <div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h3 style="font-size:13px;color:#718096;font-weight:500;margin-bottom:8px;">Jurnal</h3>
                        <div style="font-size:24px;font-weight:700;color:#2d3748;">{{ number_format($jurnal,0,',','.') }}</div>
                    </div>
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;background:#fed7d7;color:#f56565;">🕐</div>
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
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">{{ number_format($jurnal,0,',','.') }}</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">-</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Saldo Kas</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">{{ number_format($cashBalance,0,',','.') }}</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Cached from accounts</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Hutang Usaha (sisa)</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">{{ number_format($hutangUsaha,0,',','.') }}</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Sum of payables.remaining_amount</td>
                        </tr>
                        <tr>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Piutang Usaha (sisa)</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">{{ number_format($piutangUsaha,0,',','.') }}</td>
                            <td style="padding:16px 12px;border-bottom:1px solid #f7fafc;">Sum of receivables.remaining_amount</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>