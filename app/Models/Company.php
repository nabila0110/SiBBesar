<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid','name','legal_name','registration_number','tax_id','industry',
        'address_line1','address_line2','city','state','postal_code','country',
        'phone','email','website','logo_path','default_currency','accounting_method',
        'fiscal_year_start_month','fiscal_year_start_day','default_vat_rate','default_withholding_rate',
        'bank_details','primary_contact','settings','is_active'
    ];
}
