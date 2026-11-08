<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhProvince extends Model
{
    protected $fillable = ['name', 'region_name'];

    public function cities(): HasMany
    {
        return $this->hasMany(PhCity::class, 'province_id');
    }
}
