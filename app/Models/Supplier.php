<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['kode_supplier', 'nama_supplier'];
    
    /**
     * Get the barang for the supplier
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'supplier_id');
    }
}
