@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Saldo Kas</div>
            <div class="stat-value">40,689</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Hutang Usaha</div>
            <div class="stat-value">10293</div>
        </div>
        <div class="stat-icon yellow">
            <i class="fas fa-box"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Piutang Usaha</div>
            <div class="stat-value">$89,000</div>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Data Akun</div>
            <a href="#" class="stat-link">Lihat disini</a>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Ubah Perusahaan</div>
            <a href="#" class="stat-link">Klik disini</a>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Neraca</div>
            <a href="#" class="stat-link">Klik disini</a>
        </div>
        <div class="stat-icon yellow">
            <i class="fas fa-box"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">laba Rugi</div>
            <a href="#" class="stat-link">Klik disini</a>
        </div>
        <div class="stat-icon green">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Jurnal</div>
            <div class="stat-value">2040</div>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
    </div>
</div>

<!-- Project Details Section -->
<div class="project-section">
    <div class="section-header">
        <h2 class="section-title">Project Details</h2>
        <div class="section-filter">
            <span>October</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>

    <table class="project-table">
        <thead>
            <tr>
                <th>Nama Projek</th>
                <th>Lokasi</th>
                <th>Tenggat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="project-info">
                        <img src="{{ asset('images/project1.jpg') }}" alt="Project" class="project-avatar">
                        <span class="project-name">Gedung Integrated Class</span>
                    </div>
                </td>
                <td class="project-location">Jl. Bina Krida, Panam</td>
                <td class="project-date">12.09.2019 - 12:53 PM</td>
                <td>
                    <span class="status-badge terkirim">Terkirim</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="project-info">
                        <img src="{{ asset('images/project2.jpg') }}" alt="Project" class="project-avatar">
                        <span class="project-name">Perpustakaan UNRI</span>
                    </div>
                </td>
                <td class="project-location">Jl. Bina Krida, Panam</td>
                <td class="project-date">12.09.2019 - 12:53 PM</td>
                <td>
                    <span class="status-badge pending">Pending</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="project-info">
                        <img src="{{ asset('images/project3.jpg') }}" alt="Project" class="project-avatar">
                        <span class="project-name">Jembatan Leton</span>
                    </div>
                </td>
                <td class="project-location">Jl. Perjuangan, Rumbai</td>
                <td class="project-date">12.09.2019 - 12:53 PM</td>
                <td>
                    <span class="status-badge ditolak">Ditolak</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection