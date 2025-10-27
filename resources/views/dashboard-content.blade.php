@php
    // Controller should pass: $saldoKas, $hutangUsaha, $piutangUsaha, $journalCount
    $cashBalance = $saldoKas ?? 0;
    $hutangUsaha = $hutangUsaha ?? 0;
    $piutangUsaha = $piutangUsaha ?? 0;
    $jurnal = $journalCount ?? 0;
@endphp

@section('title', 'Dashboard - SiBBesar')
@section('content')
<h1>Dashboard</h1>

<!-- Dashboard Content -->
<div style="background:white;padding:24px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:30px;">
    <h2 style="font-size:18px;font-weight:600;margin:0 0 20px 0;">Selamat Datang di SiBBesar</h2>
    <p style="color:#718096;margin:0;">Sistem Informasi Bisnis Besar untuk mengelola persediaan, akuntansi, dan laporan keuangan perusahaan Anda.</p>
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
@endsection
