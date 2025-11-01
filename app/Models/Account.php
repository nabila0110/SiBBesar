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
        'account_category_id','account_type_id','group','expense_type'
    ];

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class);
    }

    /**
     * Get balance for this account. If $start/$end provided, compute from journal details,
     * otherwise return cached debit-credit.
     * Positive = debit balance, Negative = credit balance
     */
    public function getBalance($start = null, $end = null)
    {
        if (!$start && !$end) {
            $debit = $this->balance_debit ?? 0;
            $credit = $this->balance_credit ?? 0;
            return $debit - $credit;
        }

        $query = $this->journalDetails()->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('COALESCE(SUM(journal_details.debit),0) as total_debit, COALESCE(SUM(journal_details.credit),0) as total_credit');

        if ($start) $query->where('journals.transaction_date', '>=', $start);
        if ($end) $query->where('journals.transaction_date', '<=', $end);

        $totals = $query->first();
        $debit = $totals->total_debit ?? 0;
        $credit = $totals->total_credit ?? 0;
        return $debit - $credit;
    }
}
