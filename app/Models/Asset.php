<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['account_id','asset_name','description','purchase_date','purchase_price','depreciation_rate','location','condition','status'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
