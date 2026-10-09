<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'iot_kit_id',
        'quantity',
        'borrow_date',
        'expected_return_date',
        'actual_return_date',
        'fine_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date'          => 'date',
        'expected_return_date' => 'date',
        'actual_return_date'   => 'date',
        'fine_amount'          => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function iotKit(): BelongsTo
    {
        return $this->belongsTo(IoTKit::class, 'iot_kit_id');
    }

    public function damageReport(): HasOne
    {
        return $this->hasOne(DamageReport::class);
    }

    /**
     * Hitung denda keterlambatan.
     * Formula: max(0, hari_terlambat * 5000)
     */
    public function hitungDenda(\Carbon\Carbon $actualReturn): int
    {
        $hariTerlambat = $this->expected_return_date->diffInDays($actualReturn, false);

        return (int) max(0, $hariTerlambat * 5000);
    }
}
