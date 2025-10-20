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

        $prefix = sprintf('JRN/%s/%s/', $year, $month);
        // Get the last journal for this prefix and increment its numeric suffix
        $last = static::where('journal_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (! $last) {
            $next = 1;
        } else {
            $parts = explode('/', $last->journal_no);
            $suffix = intval(end($parts));
            $next = $suffix + 1;
        }

        return sprintf('%s%04d', $prefix, $next);
    }
}
