<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhCity extends Model
{
    protected $fillable = ['name', 'province_id', 'zip_code'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(PhProvince::class, 'province_id');
    }

    public function barangays(): HasMany
    {
        return $this->hasMany(PhBarangay::class, 'city_id');
    }
}
