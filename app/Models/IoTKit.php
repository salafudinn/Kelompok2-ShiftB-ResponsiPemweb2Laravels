<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IoTKit extends Model
{
    use HasFactory;

    protected $table = 'iot_kits';

    protected $fillable = [
        'code',
        'name',
        'category',
        'storage_location',
        'stock',
        'status',
        'image_path',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'iot_kit_id');
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class, 'iot_kit_id');
    }
}
