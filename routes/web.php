<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/

// Halaman login (root diarahkan ke login)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/app', function () {
    return view('layouts.app');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\NeracaSaldoController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferencesController;

// Dashboard (hanya bisa diakses jika sudah login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return redirect('/login')->withErrors([
        'email' => 'Email atau password tidak sesuai.',
    ])->withInput();
});

/*
|--------------------------------------------------------------------------
| LOGOUT ROUTE
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ORIGINAL SYSTEM ROUTES
|--------------------------------------------------------------------------
*/

// Resource Routes (CRUD)
Route::resource('hutang', HutangController::class);
Route::resource('piutang', PiutangController::class);
Route::resource('asset', AssetController::class);
Route::resource('akun', AkunController::class);
Route::resource('jurnal', JurnalController::class);
Route::resource('buku-besar', BukuBesarController::class);
Route::resource('barang', DataBarangController::class);
Route::resource('jenis-barang', JenisBarangController::class);
Route::resource('barang', DataBarangController::class)->only([
    'index', 'store', 'edit', 'update', 'destroy'
]);
Route::resource('supplier', SupplierController::class);

// Single Page Routes (Reports & Special Pages)
Route::get('/neraca', [NeracaSaldoController::class, 'index'])->name('neraca');
Route::get('/neraca-saldo-awal', [NeracaSaldoController::class, 'awal'])->name('neraca-saldo-awal');
Route::get('/neraca-saldo-akhir', [NeracaSaldoController::class, 'akhir'])->name('neraca-saldo-akhir');
Route::get('/laporan-transaksi', [LaporanKeuanganController::class, 'transaksi'])->name('laporan-transaksi');
Route::get('/laporan-posisi-keuangan', [LaporanKeuanganController::class, 'posisi'])->name('laporan-posisi-keuangan');
Route::get('/laporan-laba-rugi', [LaporanKeuanganController::class, 'labaRugi'])->name('laporan-laba-rugi');
Route::get('/backup-database', [BackupController::class, 'index'])->name('backup-database');
Route::post('/backup-database/create', [BackupController::class, 'create'])->name('backup-database.create');
Route::post('/pph21/calculate', [CompanyController::class, 'calculate'])->name('pph21.calculate');

// Simple profile & preferences routes used by the navbar dropdown and sidebar header.
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile')->middleware('auth');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

Route::get('/pengaturan', [PreferencesController::class, 'index'])->name('pengaturan')->middleware('auth');

// Logout route fallback (ensures route('logout') exists)
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


