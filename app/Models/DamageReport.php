<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'iot_kit_id',
        'reporter_id',
        'damage_type',
        'description',
        'repair_status',
        'repair_note',
        'repairer_name',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function iotKit(): BelongsTo
    {
        return $this->belongsTo(IoTKit::class, 'iot_kit_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(DamageReportImage::class);
    }
}
