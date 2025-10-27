<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiBBesar')</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @if(Request::routeIs('pph21.*'))
        <link rel="stylesheet" href="{{ asset('css/pph21.css') }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    @stack('styles')

</head>

<body>
    <div class="app-container">
        <button class="sidebar-fixed-toggle" id="sidebarFixedToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-header-left">
                    <h2 class="logo">SiBBesar</h2>
                </div>
                <div class="sidebar-header-right">
                    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Akuntansi Group -->
                <div class="nav-group">
                    <div class="nav-group-title">Akuntansi</div>
                    <a href="{{ route('hutang.index') }}"
                        class="nav-item {{ Request::routeIs('hutang.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Daftar Hutang</span>
                    </a>
                    <a href="{{ route('piutang.index') }}"
                        class="nav-item {{ Request::routeIs('piutang.*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>Daftar Piutang</span>
                    </a>
                    <a href="{{ route('asset.index') }}"
                        class="nav-item {{ Request::routeIs('asset.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Daftar Aset</span>
                    </a>
                    <a href="{{ route('akun.index') }}"
                        class="nav-item {{ Request::routeIs('akun.*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Daftar Akun</span>
                    </a>
                    <a href="{{ route('jurnal.index') }}"
                        class="nav-item {{ Request::routeIs('jurnal.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Jurnal Umum</span>
                    </a>
                    <a href="{{ route('buku-besar.index') }}"
                        class="nav-item {{ Request::routeIs('buku-besar.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        <span>Buku Besar</span>
                    </a>
                    <a href="{{ route('neraca-saldo-awal') }}"
                        class="nav-item {{ Request::routeIs('neraca-saldo-awal') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i>
                        <span>Neraca Saldo Awal</span>
                    </a>
                    <a href="{{ route('neraca-saldo-akhir') }}"
                        class="nav-item {{ Request::routeIs('neraca-saldo-akhir') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale-right"></i>
                        <span>Neraca Saldo Akhir</span>
                    </a>
                </div>

                <!-- Laporan Group -->
                <div class="nav-group">
                    <div class="nav-group-title">Laporan</div>
                    <a href="{{ route('laporan-posisi-keuangan') }}"
                        class="nav-item {{ Request::routeIs('laporan-posisi-keuangan') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Posisi Keuangan</span>
                    </a>
                    <a href="{{ route('laporan-laba-rugi') }}"
                        class="nav-item {{ Request::routeIs('laporan-laba-rugi') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan Laba Rugi</span>
                    </a>
                </div>

                <!-- Persediaan Group -->
                <div class="nav-group">
                    <div class="nav-group-title">Persediaan</div>
                    <a href="{{ route('barang.index') }}"
                        class="nav-item {{ Request::routeIs('barang.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <span>Data Barang</span>
                    </a>
                    <a href="{{ route('jenis-barang.index') }}"
                        class="nav-item {{ Request::routeIs('jenis-barang.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Jenis Barang</span>
                    </a>
                    <a href="{{ route('merk-barang.index') }}"
                        class="nav-item {{ Request::routeIs('merk-barang.*') ? 'active' : '' }}">
                        <i class="fas fa-certificate"></i>
                        <span>Merk Barang</span>
                    </a>
                    <a href="{{ route('supplier.index') }}"
                        class="nav-item {{ Request::routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <span>Supplier Barang</span>
                    </a>
                </div>

                <!-- Perhitungan Group -->
                <div class="nav-group">
                    <div class="nav-group-title">Perhitungan</div>
                    <a href="{{ route('pph21.index') }}"
                        class="nav-item {{ Request::routeIs('pph21.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Pajak Penghasilan</span>
                    </a>
                </div>

                <!-- System Group -->
                <div class="nav-group">
                    <div class="nav-group-title">System</div>
                    <a href="{{ route('backup-database') }}"
                        class="nav-item {{ Request::routeIs('backup-database') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Backup Database</span>
                    </a>
                </div>
            </nav>
        </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-left">
                    {{-- reserved for potential page controls --}}
                </div>
                <div class="navbar-right">
                    <div class="user-profile">
                        <img src="{{ asset('images/logo_pt.jpg') }}" alt="User Avatar" class="user-avatar">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'Nabila' }}</span>
                            <span class="user-role">Admin</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/layout.js') }}"></script>
    @stack('scripts')
</body>

</html>
