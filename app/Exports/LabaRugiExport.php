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

class LabaRugiExport implements FromCollection, WithHeadings, WithStyles, WithTitle
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
        
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->where('is_active', true)->orderBy('code');
        }])
        ->whereIn('type', ['revenue', 'expense'])
        ->orderBy('code')
        ->get();
        
        $totalRevenue = 0;
        $totalExpense = 0;
        
        foreach ($categories as $category) {
            $data->push([
                'kode' => $category->code . '-' . $category->name,
                'nama' => '',
                'jumlah' => ''
            ]);
            
            $categoryTotal = 0;
            
            foreach ($category->accounts as $account) {
                $balance = $this->calculateAccountBalance($account->id);
                $categoryTotal += $balance;
                
                $data->push([
                    'kode' => $category->code . '-' . $account->code,
                    'nama' => $account->name,
                    'jumlah' => $balance
                ]);
            }
            
            $data->push([
                'kode' => '',
                'nama' => 'TOTAL ' . strtoupper($category->name),
                'jumlah' => $categoryTotal
            ]);
            
            if ($category->type === 'revenue') {
                $totalRevenue = $categoryTotal;
            } else {
                $totalExpense = $categoryTotal;
            }
            
            $data->push(['kode' => '', 'nama' => '', 'jumlah' => '']);
        }
        
        $netIncome = $totalRevenue - $totalExpense;
        $data->push([
            'kode' => '',
            'nama' => $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH',
            'jumlah' => abs($netIncome)
        ]);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Jumlah'
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
        return 'Laba Rugi';
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
        }
        
        return 0;
    }
}
