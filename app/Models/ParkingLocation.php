<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'road_section_id',
        'name',
        'status',
    ];

    //Relaso RoadSection
    public function roadSection()
    {
        return $this->belongsTo(RoadSection::class);
    }

    // Relasi ke Agreements melalui tabel pivot agreement_parking_locations
    public function agreements()
    {
        return $this->belongsToMany(Agreement::class, 'agreement_parking_locations')
            ->withPivot('assigned_date', 'removed_date', 'status')
            ->withTimestamps();
    }
}
