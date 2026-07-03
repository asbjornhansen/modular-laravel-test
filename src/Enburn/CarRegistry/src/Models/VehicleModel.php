<?php

namespace Enburn\CarRegistry\Models;

use Enburn\CarRegistry\Database\Factories\VehicleModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'number_of_seats',
        'fuel_type',
        'brand_id',
    ];

    protected static function newFactory()
    {
        return VehicleModelFactory::new();
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'vehicle_id', 'id');
    }
}
