@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@push('styles')
<style>
    .stat-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .stat-card a {
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Buku Besar -->
    <a href="{{ route('buku-besar.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Buku Besar</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Perhitungan Pajak</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-book-open"></i>
        </div>
    </a>

    <!-- Hutang Usaha -->
    <a href="{{ route('hutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Hutang Usaha</div>
            <div class="stat-value">Rp {{ number_format($totalPayables, 0, ',', '.') }}</div>
        </div>
        <div class="stat-icon yellow">
            <i class="fas fa-money-bill-wave"></i>
        </div>
    </a>

    <!-- Piutang Usaha -->
    <a href="{{ route('piutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Piutang Usaha</div>
            <div class="stat-value">Rp {{ number_format($totalRecievables, 0, ',', '.') }}</div>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-hand-holding-usd"></i>
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
</div>

<!-- Second Row Stats -->
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

    <!-- Laporan Posisi Keuangan -->
    <a href="{{ route('laporan-posisi-keuangan') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Neraca</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Posisi Keuangan</div>
        </div>
        <div class="stat-icon blue">
            <i class="fas fa-balance-scale"></i>
        </div>
    </a>

    <!-- Laporan Laba Rugi -->
    <a href="{{ route('laporan-laba-rugi') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Laba Rugi</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Laporan P&L</div>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-chart-bar"></i>
        </div>
    </a>

    <!-- Pajak Penghasilan -->
    <a href="{{ route('pph21.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">PPh 21</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Perhitungan Pajak</div>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-calculator"></i>
        </div>
    </a>

    <!-- Neraca Saldo Awal -->
    <a href="{{ route('neraca-saldo-awal') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Neraca Saldo</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Awal Periode</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-file-alt"></i>
        </div>
    </a>

    <!-- Neraca Saldo Akhir -->
    <a href="{{ route('neraca-saldo-akhir') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Neraca Saldo</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Akhir Periode</div>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-file-alt"></i>
        </div>
    </a>
</div>

<!-- Additional Links Row -->
<div class="stats-grid" style="margin-top: 18px;">
    <!-- Daftar Aset -->
    <a href="{{ route('asset.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Daftar Aset</div>
            <div class="stat-label" style="font-size: 12px; color: #6b7280;">Manajemen Aset</div>
        </div>
        <div class="stat-icon blue">
            <i class="fas fa-warehouse"></i>
        </div>
    </a>

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