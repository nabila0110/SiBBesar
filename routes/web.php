<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\JournalDetailController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AuditLogController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Minimal login route so tests that expect route('login') resolve. Adjust to your auth scaffold later.
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Handle login POST from the login form
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
});

Route::resource('accounts', AccountController::class);
Route::resource('journals', JournalController::class);
// Journal details handled via nested routes for creation/update/destroy
Route::post('journals/{journal}/details', [JournalDetailController::class, 'store'])->name('journals.details.store');
Route::put('journal-details/{journalDetail}', [JournalDetailController::class, 'update'])->name('journal-details.update');
Route::delete('journal-details/{journalDetail}', [JournalDetailController::class, 'destroy'])->name('journal-details.destroy');

Route::resource('receivables', ReceivableController::class)->only(['index','create','store']);
Route::resource('payables', PayableController::class)->only(['index','create','store']);
Route::resource('assets', AssetController::class)->only(['index','create','store']);
Route::resource('users', UserController::class)->only(['index','edit','update']);

Route::get('reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');
Route::get('reports/income-statement', [ReportController::class, 'incomeStatement'])->name('reports.income-statement');
Route::get('reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
Route::get('reports/general-ledger', [ReportController::class, 'generalLedger'])->name('reports.general-ledger');

Route::resource('companies', CompanyController::class)->only(['index','edit']);
Route::resource('account-categories', AccountCategoryController::class)->only(['index']);
Route::resource('account-types', AccountTypeController::class)->only(['index']);
Route::resource('audit-logs', AuditLogController::class)->only(['index']);
