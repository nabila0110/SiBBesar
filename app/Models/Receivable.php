<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_no','account_id','customer_name','invoice_date','due_date','amount','paid_amount','remaining_amount','status','notes'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
