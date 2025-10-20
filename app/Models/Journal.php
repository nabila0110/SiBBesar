<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = ['journal_no','transaction_date','description','reference','total_debit','total_credit','status','created_by','approved_by'];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateJournalNo()
    {
        $now = Carbon::now();
        $year = $now->format('Y');
        $month = $now->format('m');

        $count = static::whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->count() + 1;

        return sprintf('JRN/%s/%s/%04d', $year, $month, $count);
    }
}
