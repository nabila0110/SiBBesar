@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Jurnal Umum -->
    <a href="{{ route('jurnal.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Jurnal Umum</div>
            <div class="stat-value">{{ $journalCount }}</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-book"></i>
        </div>
    </a>

    <!-- Data Akun -->
    <a href="{{ route('akun.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Data Akun</div>
            <div class="stat-value">{{ $accountCount }}</div>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-list-alt"></i>
        </div>
    </a>
    
    <!-- Hutang -->
    <a href="{{ route('hutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Hutang</div>
            <div class="stat-label" style="font-size: 14px; color: #dc3545; font-weight: 600;">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
            <div class="stat-value" style="font-size: 18px;">{{ $countHutang }} transaksi</div>
        </div>
        <div class="stat-icon" style="background-color: #fee2e2;">
            <i class="fas fa-money-bill-wave" style="color: #dc3545;"></i>
        </div>
    </a>
    
    <!-- Piutang -->
    <a href="{{ route('piutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Piutang</div>
            <div class="stat-label" style="font-size: 14px; color: #198754; font-weight: 600;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
            <div class="stat-value" style="font-size: 18px;">{{ $countPiutang }} transaksi</div>
        </div>
        <div class="stat-icon" style="background-color: #d1fae5;">
            <i class="fas fa-hand-holding-usd" style="color: #198754;"></i>
        </div>
    </a>
</div>

<!-- Second Row Stats -->
<div class="stats-grid">
    <!-- Jurnal Um
</div>

<!-- Additional Links Row -->
<div class="stats-grid" style="margin-top: 18px;">
    <!-- Daftar Aset -->
    

    <!-- Laporan Posisi Keuangan -->
    <a href="{{ route('laporan-posisi-keuangan') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Laporan Posisi Keuangan</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Neraca</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-balance-scale"></i>
        </div>
    </a>

    <!-- Laporan Laba Rugi -->
    <a href="{{ route('laporan-laba-rugi') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Laporan Laba Rugi</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Laporan P&L</div>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-chart-line"></i>
        </div>
    </a>

    <!-- Data Barang -->
    <a href="{{ route('barang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Data Barang</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Master Barang</div>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-box"></i>
        </div>
    </a>

    <!-- Jenis Barang -->
    <a href="{{ route('jenis-barang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Jenis Barang</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Kategori Barang</div>
        </div>
        <div class="stat-icon yellow">
            <i class="fas fa-tags"></i>
        </div>
    </a>

    <!-- Supplier Barang -->
    <a href="{{ route('supplier.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Supplier Barang</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Data Suplier</div>
        </div>
        <div class="stat-icon blue">
            <i class="fas fa-truck"></i>
        </div>
    </a>
</div>

@endsection
