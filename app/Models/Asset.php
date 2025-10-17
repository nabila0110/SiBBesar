<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['asset_no','account_id','asset_name','description','purchase_date','purchase_price','depreciation_rate','accumulated_depreciation','book_value','status','notes'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
