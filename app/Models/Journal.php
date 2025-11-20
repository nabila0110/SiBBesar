<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'item',
        'quantity',
        'satuan',
        'price',
        'total',
        'tax',
        'ppn_amount',
        'final_total',
        'project',
        'company',
        'ket',
        'nota',
        'type',
        'payment_status',
        'account_id',
        'reference',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'tax' => 'boolean',
        'ppn_amount' => 'decimal:2',
        'final_total' => 'decimal:2',
    ];

    /**
     * Relationship to Account (klasifikasi)
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relationship to User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to User (updater)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessor: Determine if this is HUTANG
     * HUTANG = OUT + TIDAK LUNAS
     */
    public function getIsHutangAttribute()
    {
        return $this->type === 'out' && $this->payment_status === 'tidak_lunas';
    }

    /**
     * Accessor: Determine if this is PIUTANG
     * PIUTANG = IN + TIDAK LUNAS
     */
    public function getIsPiutangAttribute()
    {
        return $this->type === 'in' && $this->payment_status === 'tidak_lunas';
    }

    /**
     * Accessor: Get classification label (HUTANG, PIUTANG, or cash transaction)
     */
    public function getKlasifikasiLabelAttribute()
    {
        if ($this->is_hutang) {
            return 'HUTANG';
        }
        if ($this->is_piutang) {
            return 'PIUTANG';
        }
        return $this->payment_status === 'lunas' ? 'LUNAS' : '-';
    }

    public static function generateJournalNo()
    {
        $now = Carbon::now();
        $year = $now->format('Y');
        $month = $now->format('m');

        $prefix = sprintf('JRN/%s/%s/', $year, $month);
        // Get the last journal for this prefix and increment its numeric suffix
        $last = static::where('reference', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (! $last) {
            $next = 1;
        } else {
            $parts = explode('/', $last->reference);
            $suffix = intval(end($parts));
            $next = $suffix + 1;
        }

        return sprintf('%s%04d', $prefix, $next);
    }
}
