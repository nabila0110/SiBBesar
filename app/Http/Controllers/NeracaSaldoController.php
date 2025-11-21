<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Receivable;
use App\Models\Payable;
use App\Models\Journal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NeracaExport;
use App\Exports\LabaRugiExport;

class NeracaSaldoController extends Controller
{
    /**
     * Show the Neraca Saldo Awal page.
     */
    public function awal()
    {
        $year = request()->query('year', date('Y'));
        $neracaData = $this->getNeracaData($year);
        
        return view('neraca-saldo-awal', [
            'neracaData' => $neracaData,
            'year' => $year
        ]);
    }

    /**
     * Show the Laba Rugi (Income Statement) page
     */
    public function labaRugi(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        // Get only revenue and expense categories
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['revenue', 'expense'])
        ->orderBy('code')
        ->get();
        
        $labaRugiData = [];
        
        foreach ($categories as $category) {
            $categoryData = [
                'category' => $category,
                'accounts' => [],
                'total' => 0
            ];
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id, $startDate, $endDate);
                
                // Always add accounts
                $categoryData['accounts'][] = [
                    'account' => $account,
                    'balance' => $balance
                ];
                $categoryData['total'] += $balance;
            }
            
            $labaRugiData[] = $categoryData;
        }
        
        return view('laba-rugi', [
            'labaRugiData' => $labaRugiData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Show the Neraca (Balance Sheet) page - Main Report
     */
    public function index(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        // Calculate net income (Revenue - Expense) for the period
        $totalRevenue = $this->calculateTotalByType('revenue', $startDate, $endDate);
        $totalExpense = $this->calculateTotalByType('expense', $startDate, $endDate);
        $netIncome = $totalRevenue - $totalExpense;
        
        // Get only asset, liability, and equity categories (NOT revenue/expense)
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['asset', 'liability', 'equity'])
        ->orderBy('code')
        ->get();
        
        $neracaData = [];
        
        foreach ($categories as $category) {
            $categoryData = [
                'category' => $category,
                'accounts' => [],
                'total' => 0
            ];
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id, $startDate, $endDate);
                
                // For "Laba Ditahan" account, add net income
                if ($account->type === 'equity' && (stripos($account->name, 'laba') !== false || stripos($account->name, 'rugi') !== false)) {
                    $balance += $netIncome;
                }
                
                // Always add accounts for neraca even if balance is 0
                $categoryData['accounts'][] = [
                    'account' => $account,
                    'balance' => $balance
                ];
                $categoryData['total'] += $balance;
            }
            
            // Always include asset, liability, and equity categories
            $neracaData[] = $categoryData;
        }
        
        // Debug: count total accounts
        $totalAccounts = collect($neracaData)->sum(fn($cat) => count($cat['accounts']));
        \Log::info("Neraca - Total categories: " . count($neracaData) . ", Total accounts: " . $totalAccounts);
        
        return view('neraca', [
            'neracaData' => $neracaData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'netIncome' => $netIncome
        ]);
    }

    /**
     * Export Neraca to PDF
     */
    public function exportNeracaPdf(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        $totalRevenue = $this->calculateTotalByType('revenue', $startDate, $endDate);
        $totalExpense = $this->calculateTotalByType('expense', $startDate, $endDate);
        $netIncome = $totalRevenue - $totalExpense;
        
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['asset', 'liability', 'equity'])
        ->orderBy('code')
        ->get();
        
        $neracaData = [];
        
        foreach ($categories as $category) {
            $categoryData = [
                'category' => $category,
                'accounts' => [],
                'total' => 0
            ];
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id, $startDate, $endDate);
                
                if ($account->type === 'equity' && (stripos($account->name, 'laba') !== false || stripos($account->name, 'rugi') !== false)) {
                    $balance += $netIncome;
                }
                
                $categoryData['accounts'][] = [
                    'account' => $account,
                    'balance' => $balance
                ];
                $categoryData['total'] += $balance;
            }
            
            $neracaData[] = $categoryData;
        }
        
        $pdf = Pdf::loadView('pdf.neraca', [
            'neracaData' => $neracaData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'netIncome' => $netIncome
        ])->setPaper('a4', 'portrait');
        
        return $pdf->download('neraca-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Neraca to Excel
     */
    public function exportNeracaExcel(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        return Excel::download(new NeracaExport($startDate, $endDate), 'neraca-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Laba Rugi to PDF
     */
    public function exportLabaRugiPdf(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['revenue', 'expense'])
        ->orderBy('code')
        ->get();
        
        $labaRugiData = [];
        
        foreach ($categories as $category) {
            $categoryData = [
                'category' => $category,
                'accounts' => [],
                'total' => 0
            ];
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id, $startDate, $endDate);
                
                $categoryData['accounts'][] = [
                    'account' => $account,
                    'balance' => $balance
                ];
                $categoryData['total'] += $balance;
            }
            
            $labaRugiData[] = $categoryData;
        }
        
        $pdf = Pdf::loadView('pdf.laba-rugi', [
            'labaRugiData' => $labaRugiData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'portrait');
        
        return $pdf->download('laba-rugi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laba Rugi to Excel
     */
    public function exportLabaRugiExcel(Request $request)
    {
        $startDate = $request->input('dari_tanggal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));
        
        return Excel::download(new LabaRugiExport($startDate, $endDate), 'laba-rugi-' . date('Y-m-d') . '.xlsx');
    }
    
    /**
     * Calculate total for all accounts of a specific type
     */
    private function calculateTotalByType($type, $startDate, $endDate)
    {
        $accounts = Account::where('type', $type)->where('is_active', true)->pluck('id');
        
        $totalIn = Journal::whereIn('account_id', $accounts)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'in')
            ->sum('total');
            
        $totalOut = Journal::whereIn('account_id', $accounts)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'out')
            ->sum('total');
        
        if ($type === 'revenue') {
            return $totalIn - $totalOut;
        } elseif ($type === 'expense') {
            return $totalOut - $totalIn;
        }
        
        return 0;
    }
    
    /**
     * Calculate account balance for a period
     */
    private function calculateAccountBalance($accountId, $startDate, $endDate)
    {
        $account = Account::find($accountId);
        
        // Get totals from journals based on type
        $totalIn = Journal::where('account_id', $accountId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'in')
            ->sum('total');
            
        $totalOut = Journal::where('account_id', $accountId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'out')
            ->sum('total');
        
        // Logic based on account type and transaction type:
        // TYPE 'IN' = Uang masuk/penerimaan
        // TYPE 'OUT' = Uang keluar/pengeluaran
        
        // For REVENUE accounts (credit): type 'in' increases balance (positive)
        // For EXPENSE accounts (debit): type 'out' increases balance (positive for showing expense amount)
        // For ASSET accounts (debit): type 'in' increases, type 'out' decreases
        // For LIABILITY accounts (credit): type 'in' increases (terima hutang), type 'out' decreases (bayar hutang)
        
        if ($account->type === 'revenue') {
            // Revenue: uang masuk (type 'in') adalah pendapatan
            return $totalIn - $totalOut;
        } elseif ($account->type === 'expense') {
            // Expense: uang keluar (type 'out') adalah beban
            return $totalOut - $totalIn;
        } elseif ($account->normal_balance === 'debit') {
            // Asset: uang masuk menambah, uang keluar mengurangi
            return $totalIn - $totalOut;
        } else {
            // Liability, Equity: uang masuk menambah, uang keluar mengurangi
            return $totalIn - $totalOut;
        }
    }

    /**
     * Get Balance Sheet Data organized by account type
     */
    private function getNeracaData($year, $period = 'year')
    {
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();

        if ($period === 'month') {
            $month = request()->query('month', date('m'));
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        // Get all accounts grouped by type
        $assets = $this->getAccountsWithBalance('asset', $startDate, $endDate);
        $liabilities = $this->getAccountsWithBalance('liability', $startDate, $endDate);
        $equity = $this->getAccountsWithBalance('equity', $startDate, $endDate);

        // Calculate totals with receivables and payables
        $totalAssets = $assets->sum('balance') + $this->getTotalReceivables($startDate, $endDate);
        $totalLiabilities = $liabilities->sum('balance') + $this->getTotalPayables($startDate, $endDate);
        $totalEquity = $equity->sum('balance');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'receivables' => $this->getReceivablesDetail($startDate, $endDate),
            'payables' => $this->getPayablesDetail($startDate, $endDate),
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    /**
     * Get accounts with calculated balance
     */
    private function getAccountsWithBalance($type, $startDate, $endDate)
    {
        return Account::where('type', $type)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $totalIn = Journal::where('account_id', $account->id)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('type', 'in')
                    ->sum('total');
                    
                $totalOut = Journal::where('account_id', $account->id)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('type', 'out')
                    ->sum('total');

                // Calculate balance based on normal balance
                if ($account->normal_balance === 'debit') {
                    // For debit accounts: IN increases, OUT decreases
                    $balance = $totalIn - $totalOut;
                } else {
                    // For credit accounts: OUT increases, IN decreases
                    $balance = $totalOut - $totalIn;
                }

                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'description' => $account->description,
                    'debit' => (float) $debit,
                    'credit' => (float) $credit,
                    'balance' => (float) $balance,
                    'type' => $account->type,
                ];
            });
    }

    /**
     * Get receivables detail
     */
    private function getReceivablesDetail($startDate, $endDate)
    {
        return Receivable::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('account')
            ->orderBy('invoice_no')
            ->get();
    }

    /**
     * Get payables detail
     */
    private function getPayablesDetail($startDate, $endDate)
    {
        return Payable::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('account')
            ->orderBy('invoice_no')
            ->get();
    }

    /**
     * Get total receivables
     */
    private function getTotalReceivables($startDate, $endDate)
    {
        return Receivable::whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('remaining_amount') ?? 0;
    }

    /**
     * Get total payables
     */
    private function getTotalPayables($startDate, $endDate)
    {
        return Payable::whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('remaining_amount') ?? 0;
    }

    /**
     * Return neraca saldo data (debit/credit sums) for a given year as JSON.
     * Frontend calls this to render the table dynamically.
     */
    public function dataAwal(\Illuminate\Http\Request $request)
    {
        $year = $request->query('year', date('Y'));
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        // Collect accounts and compute sums from journals
        $accounts = Account::orderBy('code')->get();

        $rows = [];
        foreach ($accounts as $acct) {
            $totalIn = Journal::where('account_id', $acct->id)
                ->whereBetween('transaction_date', [$start, $end])
                ->where('type', 'in')
                ->sum('total');
                
            $totalOut = Journal::where('account_id', $acct->id)
                ->whereBetween('transaction_date', [$start, $end])
                ->where('type', 'out')
                ->sum('total');

            // For neraca saldo: type 'in' = debit, type 'out' = credit
            $debit = $totalIn;
            $credit = $totalOut;

            $rows[] = [
                'code' => $acct->code,
                'name' => $acct->name,
                'debit' => (float) $debit,
                'credit' => (float) $credit,
            ];
        }

        return response()->json(['year' => $year, 'rows' => $rows]);
    }

    /**
     * Show the Neraca Saldo Akhir page.
     */
    public function akhir()
    {
        return view('neraca-saldo-akhir');
    }
}