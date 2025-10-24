<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerkBarang extends Model
{
    use HasFactory;
    protected $fillable = ['nama_merk'];
}
