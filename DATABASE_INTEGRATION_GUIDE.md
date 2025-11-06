# 📊 Database-Integrated Accounting Reports System

## Overview

You now have a comprehensive accounting reports system fully integrated with your Laravel database. All reports are dynamically generated from your `accounts`, `journal_details`, `journals`, `receivables`, and `payables` tables through foreign key relationships.

---

## 📁 Database Structure & Foreign Key Relationships

### Core Tables

#### 1. **accounts** (Central Chart of Accounts)
```
id (PK) → referenced by:
├── journal_details.account_id (FK)
├── receivables.account_id (FK)
└── payables.account_id (FK)

Fields:
- code (VARCHAR, UNIQUE) - e.g., "1000", "2000", "5000"
- name (VARCHAR) - Full account name
- type (ENUM: asset|liability|equity|revenue|expense)
- normal_balance (ENUM: debit|credit)
- is_active (BOOLEAN)
- balance_debit / balance_credit (DECIMAL, cached values)
- description (TEXT, optional)
```

#### 2. **journals** (Transaction Headers)
```
id (PK) → referenced by:
└── journal_details.journal_id (FK)

Fields:
- journal_no (VARCHAR, UNIQUE)
- transaction_date (DATE)
- description (TEXT)
- reference (VARCHAR)
- total_debit / total_credit (DECIMAL)
- status (ENUM: draft|posted|approved)
```

#### 3. **journal_details** (Line Items) ⭐ KEY TABLE
```
id (PK)
├── journal_id (FK) → journals.id
└── account_id (FK) → accounts.id

Fields:
- description (TEXT)
- debit (DECIMAL)
- credit (DECIMAL)
- line_number (INT)

The JOIN between these three tables powers all financial reports!
```

#### 4. **receivables** (Piutang)
```
id (PK)
└── account_id (FK) → accounts.id

Fields:
- invoice_no (VARCHAR, UNIQUE)
- customer_name (VARCHAR)
- invoice_date / due_date (DATE)
- amount (DECIMAL)
- paid_amount / remaining_amount (DECIMAL)
- status (ENUM: outstanding|paid|overdue)
```

#### 5. **payables** (Hutang)
```
id (PK)
└── account_id (FK) → accounts.id

Fields:
- invoice_no (VARCHAR, UNIQUE)
- vendor_name (VARCHAR)
- invoice_date / due_date (DATE)
- amount (DECIMAL)
- paid_amount / remaining_amount (DECIMAL)
- status (ENUM: outstanding|paid|overdue)
```

---

## 🎯 Reports Built & How They Work

### 1. **Laporan Transaksi (Transaction Report)**
**Route:** `/laporan-transaksi`  
**Controller:** `LaporanKeuanganController@transaksi()`  
**View:** `resources/views/laporan-transaksi.blade.php`

**Database Query Flow:**
```
JournalDetail (with journal & account)
    ├─ Filter by journal.transaction_date BETWEEN $start AND $end
    ├─ GROUP BY account_id for summary
    ├─ GROUP BY account.type for type summary
    └─ SUM(debit), SUM(credit) for totals
```

**What It Shows:**
- Detailed transaction line-by-line with:
  - Transaction date, journal no, account code/name
  - Debit/credit amounts with number formatting
  - Running totals per page
- Summary table by account (showing count, debit, credit per account)
- Summary table by account type (asset/liability/equity/revenue/expense)
- Export to Excel functionality
- Print-friendly format

**Database Integration:**
```
SELECT 
    jd.*, 
    j.transaction_date, j.journal_no, j.description,
    a.code, a.name, a.type
FROM journal_details jd
JOIN journals j ON jd.journal_id = j.id
JOIN accounts a ON jd.account_id = a.id
WHERE j.transaction_date BETWEEN ? AND ?
ORDER BY j.transaction_date, jd.line_number
```

---

### 2. **Neraca (Balance Sheet)**
**Route:** `/neraca`  
**Controller:** `NeracaSaldoController@index()`  
**View:** `resources/views/neraca.blade.php`

**Database Query Flow:**
```
For each account type (asset/liability/equity):
    Calculate balance = SUM(debit) - SUM(credit) filtered by journal.transaction_date

Include Receivables and Payables:
    Add remaining_amount from receivables/payables tables
```

**What It Shows:**
- **AKTIVA (Assets)** section with:
  - All asset accounts grouped with balances
  - Receivables subsection with invoice details
  - Total Assets
  
- **PASSIVA** section with:
  - **Hutang (Liabilities)** with:
    - All liability accounts with balances
    - Payables subsection with invoice details
    - Total Liabilities
  - **Ekuitas (Equity)** with:
    - All equity accounts with balances
    - Total Equity
  
- **Verification section** showing:
  - Total Assets vs (Total Liabilities + Total Equity)
  - Difference must be 0 or very close (accounting principle)

**Accounting Formula (MUST BALANCE):**
```
Total Assets (AKTIVA)
= Total Liabilities (HUTANG) + Total Equity (EKUITAS)

If they don't match, there's a data entry error!
```

**Database Integration:**
```
For Assets:
  SELECT SUM(debit) - SUM(credit) FROM journal_details jd
  JOIN journals j ON jd.journal_id = j.id
  WHERE jd.account_id = ? AND a.type = 'asset'
  AND j.transaction_date BETWEEN ? AND ?

Plus Receivables:
  SELECT SUM(remaining_amount) FROM receivables
  WHERE invoice_date BETWEEN ? AND ?
```

---

### 3. **Laporan Posisi Keuangan (Financial Position Report)**
**Route:** `/laporan-posisi-keuangan`  
**Integrated with:** Account categories table

Uses account categories for hierarchical display of assets/liabilities/equity.

---

### 4. **Laporan Laba Rugi (Income Statement)**
**Route:** `/laporan-laba-rugi`  
**Integrated with:** Revenue & Expense accounts

Shows income statement structure with revenues minus expenses.

---

## 🔗 How Models & Foreign Keys Connect Everything

### Model Relationships (in `app/Models/`)

**Account Model:**
```php
public function journalDetails()
{
    return $this->hasMany(JournalDetail::class);
}

public function receivables()
{
    return $this->hasMany(Receivable::class);
}

public function payables()
{
    return $this->hasMany(Payable::class);
}

public function getBalance($start = null, $end = null)
{
    // Calculates debit - credit for given period
}
```

**JournalDetail Model:**
```php
public function journal()
{
    return $this->belongsTo(Journal::class);
}

public function account()
{
    return $this->belongsTo(Account::class);
}
```

**Journal Model:**
```php
public function details()
{
    return $this->hasMany(JournalDetail::class);
}
```

**Receivable Model:**
```php
public function account()
{
    return $this->belongsTo(Account::class);
}
```

**Payable Model:**
```php
public function account()
{
    return $this->belongsTo(Account::class);
}
```

---

## 🎨 User Interface Integration

### Navigation Menu (in `resources/views/layouts/app.blade.php`)

Added to sidebar under "Laporan" (Reports) group:
```
Laporan Transaksi          → /laporan-transaksi
Neraca (Balance Sheet)     → /neraca
Laporan Posisi Keuangan    → /laporan-posisi-keuangan
Laporan Laba Rugi          → /laporan-laba-rugi
```

Each link includes proper active state styling:
```blade
class="nav-item {{ Request::routeIs('neraca') ? 'active' : '' }}"
```

---

## 🚀 Routes Configuration

In `routes/web.php`:
```php
Route::get('/laporan-transaksi', [LaporanKeuanganController::class, 'transaksi'])->name('laporan-transaksi');
Route::get('/neraca', [NeracaSaldoController::class, 'index'])->name('neraca');
Route::get('/laporan-posisi-keuangan', [LaporanKeuanganController::class, 'posisi'])->name('laporan-posisi-keuangan');
Route::get('/laporan-laba-rugi', [LaporanKeuanganController::class, 'labaRugi'])->name('laporan-laba-rugi');
```

All routes accept optional `?year=YYYY` query parameter to filter by year.

---

## 💾 How Data Flows from Database to Reports

### Example: Neraca Report Generation

**Step 1: Controller Processes Request**
```php
$year = request()->query('year', date('Y'));
$startDate = Carbon::createFromDate($year, 1, 1);
$endDate = Carbon::createFromDate($year, 12, 31);
```

**Step 2: Query Database for Accounts**
```php
$assets = $this->getAccountsWithBalance('asset', $startDate, $endDate);
```

**Step 3: Calculate Balance for Each Account**
```php
$totals = JournalDetail::whereHas('journal', function ($query) {
    $query->whereBetween('transaction_date', [$startDate, $endDate]);
})
->where('account_id', $account->id)
->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
->first();

// Calculate balance based on normal_balance
$balance = ($account->normal_balance === 'debit') 
    ? ($totals->total_debit - $totals->total_credit)
    : ($totals->total_credit - $totals->total_debit);
```

**Step 4: Query Receivables & Payables**
```php
$receivables = Receivable::whereBetween('invoice_date', [$startDate, $endDate])
    ->with('account')
    ->get();

$totalReceivables = Receivable::whereBetween('invoice_date', [$startDate, $endDate])
    ->sum('remaining_amount');
```

**Step 5: Pass to Blade View**
```php
return view('neraca', [
    'neracaData' => [
        'assets' => $assets,
        'liabilities' => $liabilities,
        'equity' => $equity,
        'receivables' => $receivables,
        'payables' => $payables,
        'totalAssets' => $totalAssets,
        'totalLiabilities' => $totalLiabilities,
        'totalEquity' => $totalEquity,
    ]
]);
```

**Step 6: Blade Template Renders HTML**
```blade
@foreach($neracaData['assets'] as $asset)
    <tr>
        <td>{{ $asset['code'] }}</td>
        <td>{{ $asset['name'] }}</td>
        <td>Rp {{ number_format($asset['balance'], 2, ',', '.') }}</td>
    </tr>
@endforeach
```

---

## 📋 Data Validation & Accounting Rules

### Debit/Credit Rules Enforced

1. **Accounts Table:**
   - Each account has `normal_balance` field (debit or credit)
   - Balance calculation respects normal balance:
     ```
     If normal_balance = 'debit':   balance = debit - credit
     If normal_balance = 'credit':  balance = credit - debit
     ```

2. **Journal Entries:**
   - Must balance: Total Debit = Total Credit
   - System verifies this in `JournalController@store()`

3. **Receivables/Payables:**
   - `remaining_amount = amount - paid_amount`
   - Status determined by amounts and dates

### Account Types & Hierarchy

```
├── Assets (Aktiva)
│   ├── Current Assets
│   │   ├── Cash & Bank
│   │   ├── Receivables (from receivables table)
│   │   └── Inventory
│   └── Fixed Assets
│
├── Liabilities (Passiva)
│   ├── Current Liabilities
│   │   ├── Payables (from payables table)
│   │   └── Short-term Debt
│   └── Long-term Liabilities
│
└── Equity (Ekuitas)
    ├── Capital
    ├── Retained Earnings
    └── Profits/Losses
```

---

## 🔍 Key SQL Queries Used

### Query 1: Get Total Debits/Credits by Account
```sql
SELECT 
    a.id, a.code, a.name,
    SUM(jd.debit) as total_debit,
    SUM(jd.credit) as total_credit
FROM journal_details jd
JOIN journals j ON jd.journal_id = j.id
JOIN accounts a ON jd.account_id = a.id
WHERE j.transaction_date BETWEEN '2024-01-01' AND '2024-12-31'
  AND a.type = 'asset'
  AND a.is_active = 1
GROUP BY a.id
ORDER BY a.code;
```

### Query 2: Get Transaction Details for Report
```sql
SELECT 
    jd.*, 
    j.journal_no, j.transaction_date, j.description,
    a.code, a.name, a.type
FROM journal_details jd
JOIN journals j ON jd.journal_id = j.id
JOIN accounts a ON jd.account_id = a.id
WHERE j.transaction_date BETWEEN '2024-01-01' AND '2024-12-31'
ORDER BY j.transaction_date, jd.line_number
LIMIT 50;
```

### Query 3: Summary by Account Type
```sql
SELECT 
    a.type,
    COUNT(*) as count,
    SUM(jd.debit) as total_debit,
    SUM(jd.credit) as total_credit
FROM journal_details jd
JOIN journals j ON jd.journal_id = j.id
JOIN accounts a ON jd.account_id = a.id
WHERE j.transaction_date BETWEEN '2024-01-01' AND '2024-12-31'
GROUP BY a.type;
```

### Query 4: Get Outstanding Receivables/Payables
```sql
SELECT * FROM receivables
WHERE remaining_amount > 0 
  AND invoice_date BETWEEN '2024-01-01' AND '2024-12-31'
  AND status IN ('outstanding', 'overdue');
```

---

## 🎯 Features Implemented

### 1. **Dynamic Date Filtering**
- All reports support `?year=YYYY` parameter
- Can be extended to support `?month=MM` for monthly reports
- Dates validated and formatted in both database queries and display

### 2. **Number Formatting**
- Rupiah currency format: `Rp {{ number_format($value, 2, ',', '.') }}`
- Thousands separator (.) and decimal comma (,)
- Applied automatically across all reports

### 3. **Account Code Badges**
- Color-coded by account type (asset/liability/equity/revenue/expense)
- Easy visual identification in tables
- Shows full account code and name

### 4. **Export Functionality**
- Export to Excel (XLSX) using `XLSX.js` library
- Maintains table structure and formatting
- Filename includes year and report type

### 5. **Print Optimization**
- CSS media queries hide UI elements on print
- Page breaks inside cards prevented
- Professional printed format

### 6. **Responsive Design**
- Bootstrap 5 grid system
- Works on desktop, tablet, mobile
- Tables scroll on small screens

### 7. **Balance Verification Alert**
- Automatic calculation of Assets vs (Liabilities + Equity)
- Shows green check if balanced (difference < 0.01)
- Shows red warning if imbalanced (data entry error)

---

## 🛠️ How to Extend / Customize

### Adding a New Report

1. **Create Controller Method:**
```php
public function newReport(Request $request)
{
    $year = $request->get('year', date('Y'));
    $data = $this->getReportData($year);
    return view('laporan-new', compact('data', 'year'));
}
```

2. **Create Blade View:**
```blade
@extends('layouts.app')
@section('content')
  <!-- Use same patterns as existing reports -->
@endsection
```

3. **Add Route:**
```php
Route::get('/laporan-new', [ReportController::class, 'newReport'])->name('laporan-new');
```

4. **Add Menu Item:**
```blade
<a href="{{ route('laporan-new') }}" class="nav-item">
    <i class="fas fa-chart-something"></i>
    <span>Laporan Baru</span>
</a>
```

### Customizing Queries

All reports use Eloquent ORM. To modify filtering:
```php
// Add company_id filter
$assets = Account::where('type', 'asset')
    ->where('company_id', $companyId)  // ADD THIS
    ->where('is_active', true)
    ->get();
```

### Adding New Account Types

Modify the `enum` in migrations:
```php
$table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense', 'newtype']);
```

---

## 📊 Database Structure Diagram

```
┌─────────────┐
│  companies  │ (Optional: Link accounts to specific company)
└─────────────┘
      │
      │ 1:N
      ▼
┌──────────────────────┐
│     accounts         │
├──────────────────────┤
│ id (PK)              │
│ code                 │◄─────────────────┐
│ name                 │                  │
│ type (asset|...)     │                  │
│ normal_balance       │                  │
│ is_active            │                  │
│ balance_debit        │                  │
│ balance_credit       │                  │
└──────────────────────┘                  │
      ▲                                   │
      │ N:1                               │
      │                         ┌──────────────────────┐
      │                         │  journal_details     │
┌─────┴──────────────────┐      ├──────────────────────┤
│     journals           │      │ id (PK)              │
├────────────────────────┤      │ journal_id (FK) ─────┼──┐
│ id (PK)                │      │ account_id (FK) ─────┼──┤
│ journal_no             │◄─────│ debit                │  │
│ transaction_date       │ 1:N  │ credit               │  │
│ description            │      │ description          │  │
│ total_debit            │      │ line_number          │  │
│ total_credit           │      └──────────────────────┘  │
│ status                 │                                 │
└────────────────────────┘                                 │
                                                           │
┌──────────────────────┐  ┌──────────────────────┐       │
│   receivables        │  │    payables          │       │
├──────────────────────┤  ├──────────────────────┤       │
│ id (PK)              │  │ id (PK)              │       │
│ invoice_no           │  │ invoice_no           │       │
│ account_id (FK) ─────┼──┼──account_id (FK) ────┼───────┘
│ customer_name        │  │ vendor_name          │
│ invoice_date         │  │ invoice_date         │
│ amount               │  │ amount               │
│ paid_amount          │  │ paid_amount          │
│ remaining_amount     │  │ remaining_amount     │
│ status               │  │ status               │
└──────────────────────┘  └──────────────────────┘
```

---

## ✅ Testing Your Integration

### Test 1: Create Sample Accounts
```sql
INSERT INTO accounts (code, name, type, normal_balance, is_active)
VALUES ('1000', 'Kas', 'asset', 'debit', 1),
       ('2000', 'Hutang Usaha', 'liability', 'credit', 1),
       ('3000', 'Modal', 'equity', 'credit', 1);
```

### Test 2: Create Sample Journal Entry
```php
// Via form at /jurnal/create or directly:
Journal::create([
    'journal_no' => 'J0001',
    'transaction_date' => now(),
    'description' => 'Test entry'
]);

JournalDetail::create([
    'journal_id' => 1,
    'account_id' => 1, // Kas (debit)
    'debit' => 100000,
    'credit' => 0
]);

JournalDetail::create([
    'journal_id' => 1,
    'account_id' => 2, // Hutang Usaha (credit)
    'debit' => 0,
    'credit' => 100000
]);
```

### Test 3: View Reports
1. Go to `/laporan-transaksi` - Should show your entry
2. Go to `/neraca` - Should show Kas = 100,000 (asset) and Hutang = 100,000 (liability)
3. Check that difference shows 0 (Assets = Liabilities)

---

## 🎓 Summary

Your accounting system now has:

✅ **Fully integrated database** - All data flows through proper foreign key relationships  
✅ **Multiple financial reports** - Transaction detail, Balance Sheet, Income Statement, Position  
✅ **Real-time calculations** - Balances calculated on-demand from journal entries  
✅ **Professional formatting** - Rupiah currency, number formatting, accounting hierarchy  
✅ **Export & Print** - Excel export, print-friendly CSS  
✅ **Date filtering** - Year-based report filtering  
✅ **Data validation** - Automatic balance checking, accounting rules enforced  
✅ **Responsive UI** - Works on all devices  

The system respects double-entry bookkeeping principles where **every transaction has equal debits and credits**, and the balance sheet always balances: **Assets = Liabilities + Equity**
