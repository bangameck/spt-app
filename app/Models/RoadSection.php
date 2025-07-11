<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    //Relaso ParkingLocation
    public function ParkingLocation()
    {
        return $this->hasMany(ParkingLocation::class);
    }
}
