<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiBBesar')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_wb.png') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=' . time()) }}">
    @if(Request::routeIs('pph21.*'))
        <link rel="stylesheet" href="{{ asset('css/pph21.css') }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
                </div>

                <!-- Laporan Group -->
                <div class="nav-group">
                <div class="nav-group-title">Laporan</div>
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
                    <a href="{{ route('hutang.index') }}"
                        class="nav-item {{ Request::routeIs('hutang.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Hutang</span>
                    </a>
                    <a href="{{ route('piutang.index') }}"
                        class="nav-item {{ Request::routeIs('piutang.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Piutang</span>
                    </a>
                    <a href="{{ route('neraca') }}"
                        class="nav-item {{ Request::routeIs('neraca') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i>
                        <span>Neraca (Balance Sheet)</span>
                    </a>
                    <a href="{{ route('laba-rugi') }}"
                        class="nav-item {{ Request::routeIs('laba-rugi') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laba Rugi (Income Statement)</span>
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
                    <a href="{{ route('supplier.index') }}"
                        class="nav-item {{ Request::routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <span>Supplier Barang</span>
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
                    <div class="user-profile-container">
                        <button class="user-profile" id="userProfileBtn" onclick="toggleUserMenu(event)">
                            <img src="{{ asset('images/logo_wb.png') }}" alt="User Avatar" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name ?? 'Nabila' }}</span>
                                <span class="user-role">Admin</span>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="user-dropdown-menu" id="userDropdownMenu">
                            <div class="dropdown-header">
                                <img src="{{ asset('images/logo_pt.jpg') }}" alt="User Avatar" class="dropdown-avatar">
                                <div>
                                    <div class="dropdown-user-name">{{ Auth::user()->name ?? 'Nabila' }}</div>
                                    <div class="dropdown-user-email">{{ Auth::user()->email ?? 'admin@sibbesar.com' }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile') }}" class="dropdown-item profile-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Profil Saya</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>

            <!-- Page Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Custom Alert Modal -->
    <div id="customAlertOverlay" class="custom-alert-overlay">
        <div class="custom-alert-modal">
            <img id="alertLogo" class="custom-alert-logo" src="{{ asset('images/logo_pt.jpg') }}" alt="Logo">
            <div id="alertIcon" class="custom-alert-icon success">
                <i class="fas fa-check"></i>
            </div>
            <div id="alertTitle" class="custom-alert-title">Sukses</div>
            <div id="alertMessage" class="custom-alert-message">Operasi berhasil dilakukan</div>
            <div class="custom-alert-buttons">
                <button type="button" class="custom-alert-btn primary" onclick="closeCustomAlert()">OK</button>
            </div>
        </div>
    </div>

    <!-- jQuery - harus dimuat PERTAMA -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js - PENTING untuk x-data, x-model, x-show, x-text -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('js/layout.js') }}"></script>
    @stack('scripts')
</body>

</html>