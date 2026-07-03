<?php

namespace Enburn\CarRegistry\Models;

use Enburn\CarRegistry\Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected static function newFactory()
    {
        return VehicleFactory::new();
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function scopeFilterBrandName($query, string $name)
    {
        return $query->whereHas('brand',function($query) use ($name) {
            $query->where('name', 'like', $name);
        })
            ->get();
    }

    public function scopeFilterModelName($query, string $name)
    {
        return $query->whereHas('brand.vehicleModels', function($query) use ($name) {
            $query->where('name', 'like', $name);
        })
        ->get();
    }
}
