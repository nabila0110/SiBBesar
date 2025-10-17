<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = ['journal_no','transaction_date','description','reference','total_debit','total_credit','status','created_by','approved_by'];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
