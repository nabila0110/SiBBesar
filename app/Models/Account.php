<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'code','name','type','normal_balance','is_active',
        'balance_debit','balance_credit','description',
        'account_type_id','group','expense_type'
    ];

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class, 'account_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'account_id');
    }

    /**
     * Get full account code
     */
    public function getFullCodeAttribute()
    {
        return $this->code;
    }

    /**
     * Get balance for this account - HANYA HITUNG JURNAL UTAMA
     */
    public function getBalance($start = null, $end = null)
    {
        if (!$start && !$end) {
            $debit = $this->balance_debit ?? 0;
            $credit = $this->balance_credit ?? 0;
            return $debit - $credit;
        }

        // PERBAIKAN: Hanya hitung jurnal utama (is_paired = false)
        $query = $this->journals()
            ->where('is_paired', false)
            ->selectRaw('COALESCE(SUM(debit),0) as total_debit, COALESCE(SUM(kredit),0) as total_credit');

        if ($start) $query->where('transaction_date', '>=', $start);
        if ($end) $query->where('transaction_date', '<=', $end);

        $totals = $query->first();
        $debit = $totals->total_debit ?? 0;
        $credit = $totals->total_credit ?? 0;
        
        // Hitung berdasarkan normal balance
        // Asset & Expense (normal balance debit): debit - kredit
        // Liability, Equity, Revenue (normal balance kredit): kredit - debit
        if (in_array($this->type, ['asset', 'expense'])) {
            return $debit - $credit;
        } else {
            return $credit - $debit;
        }
    }
}
