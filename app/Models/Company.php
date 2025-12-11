<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'code',
        'name',
        'logo',
        'description',
        'address',
        'phone',
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get all journals for this company
     */
    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    /**
     * Get active journals only
     */
    public function activeJournals()
    {
        return $this->hasMany(Journal::class)->where('status', 'posted');
    }

    /**
     * Scope untuk filter hanya company aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
