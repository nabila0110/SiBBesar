<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_no','account_id','vendor_name','invoice_date','due_date','amount','paid_amount','remaining_amount','status','notes'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
