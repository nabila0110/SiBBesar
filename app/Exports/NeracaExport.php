<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NeracaExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = collect();
        
        // Get categories
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['asset', 'liability', 'equity'])
        ->orderBy('code')
        ->get();
        
        // Calculate net income
        $totalRevenue = $this->calculateTotalByType('revenue');
        $totalExpense = $this->calculateTotalByType('expense');
        $netIncome = $totalRevenue - $totalExpense;
        
        foreach ($categories as $category) {
            // Add category header
            $data->push([
                'kode' => $category->code . '-' . $category->name,
                'nama' => '',
                'saldo' => ''
            ]);
            
            $categoryTotal = 0;
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id);
                
                if ($account->type === 'equity' && (stripos($account->name, 'laba') !== false || stripos($account->name, 'rugi') !== false)) {
                    $balance += $netIncome;
                }
                
                $categoryTotal += $balance;
                
                $data->push([
                    'kode' => $category->code . '-' . $account->code,
                    'nama' => $account->name,
                    'saldo' => $balance
                ]);
            }
            
            // Add category total
            $data->push([
                'kode' => '',
                'nama' => 'TOTAL ' . strtoupper($category->name),
                'saldo' => $categoryTotal
            ]);
            
            $data->push(['kode' => '', 'nama' => '', 'saldo' => '']); // Empty row
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Saldo'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Neraca';
    }

    private function calculateAccountBalance($accountId)
    {
        $account = Account::find($accountId);
        
        $totalIn = Journal::where('account_id', $accountId)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->where('type', 'in')
            ->sum('total');
            
        $totalOut = Journal::where('account_id', $accountId)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->where('type', 'out')
            ->sum('total');
        
        if ($account->type === 'revenue') {
            return $totalIn - $totalOut;
        } elseif ($account->type === 'expense') {
            return $totalOut - $totalIn;
        } elseif ($account->normal_balance === 'debit') {
            return $totalIn - $totalOut;
        } else {
            return $totalIn - $totalOut;
        }
    }

    private function calculateTotalByType($type)
    {
        $accounts = Account::where('type', $type)->where('is_active', true)->pluck('id');
        
        $totalIn = Journal::whereIn('account_id', $accounts)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->where('type', 'in')
            ->sum('total');
            
        $totalOut = Journal::whereIn('account_id', $accounts)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->where('type', 'out')
            ->sum('total');
        
        if ($type === 'revenue') {
            return $totalIn - $totalOut;
        } elseif ($type === 'expense') {
            return $totalOut - $totalIn;
        }
        
        return 0;
    }
}
