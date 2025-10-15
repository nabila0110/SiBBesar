<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Akuntansi</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
                body { background: #f3f7fa; }
                .sidebar { background: #eaf1fb; min-height: 100vh; }
                .sidebar .nav-link.active { background: #3b5bdb; color: #fff !important; }
                .sidebar .nav-link { color: #3b5bdb; }
                .card-icon { font-size: 2rem; }
                .card { border-radius: 1rem; }
                .profile-card { min-height: 100px; }
        </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar py-4">
            <div class="sidebar-sticky">
                <h3 class="text-primary ms-3 mb-4">Akuntansi :)</h3>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Daftar Hutang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Daftar Piutang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Daftar Aset</a></li>
                </ul>
                <h6 class="text-secondary ms-3">Akuntansi</h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="#">Daftar Akun</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Jurnal Umum</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Buku Besar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Neraca Saldo Awal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Neraca Saldo Akhir</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard</h2>
                <div class="d-flex align-items-center">
                    <span class="me-2">Keuangan</span>
                    <span class="badge bg-secondary">Sedang Login</span>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-primary mb-2"><i class="bi bi-wallet2"></i></div>
                        <div>Saldo Kas</div>
                        <div class="fw-bold">Rp. 49.000.000</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-success mb-2"><i class="bi bi-credit-card"></i></div>
                        <div>Hutang Usaha</div>
                        <div class="fw-bold">Rp. 0</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-info mb-2"><i class="bi bi-credit-card-2-front"></i></div>
                        <div>Piutang Usaha</div>
                        <div class="fw-bold">Rp. 0</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-danger mb-2"><i class="bi bi-search"></i></div>
                        <div>Data Akun</div>
                        <a href="#" class="fw-bold text-decoration-none">Lihat Disini</a>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-danger mb-2"><i class="bi bi-file-earmark-text"></i></div>
                        <div>Laporan</div>
                        <a href="#" class="fw-bold text-decoration-none">Posisi Keuangan</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm text-center p-3">
                        <div class="card-icon text-primary mb-2"><i class="bi bi-file-earmark-text"></i></div>
                        <div>Laporan</div>
                        <a href="#" class="fw-bold text-decoration-none">Laba Rugi</a>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3 profile-card">
                        <div class="card-icon text-primary mb-2"><i class="bi bi-person"></i></div>
                        <div>Ubah Profil</div>
                        <a href="#" class="fw-bold text-decoration-none">Klik Disini</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3 profile-card">
                        <div class="card-icon text-success mb-2"><i class="bi bi-envelope"></i></div>
                        <div>Ubah Email</div>
                        <a href="#" class="fw-bold text-decoration-none">Klik Disini</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3 profile-card">
                        <div class="card-icon text-info mb-2"><i class="bi bi-person-badge"></i></div>
                        <div>Ubah Nama</div>
                        <a href="#" class="fw-bold text-decoration-none">Klik Disini</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center p-3 profile-card">
                        <div class="card-icon text-danger mb-2"><i class="bi bi-key"></i></div>
                        <div>Ubah Password</div>
                        <a href="#" class="fw-bold text-decoration-none">Klik Disini</a>
                    </div>
                </div>
            </div>
            <footer class="text-center text-secondary mt-5">
                <small>2021 © Akuntansi:) | Crafted with <span class="text-danger">♥</span> by Geeveloper</small>
            </footer>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>
</html>
    
</body>
</html>