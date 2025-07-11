<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory; // Tidak perlu HasFactory untuk Pivot
use Illuminate\Database\Eloquent\Relations\Pivot; // <-- UBAH INI
use Illuminate\Database\Eloquent\SoftDeletes; // Tetap gunakan jika Anda ingin soft delete pada pivot

class AgreementParkingLocation extends Pivot // <-- UBAH INI
{
    // use HasFactory, SoftDeletes; // HasFactory biasanya tidak digunakan untuk Pivot
    use SoftDeletes; // Tetap gunakan SoftDeletes jika Anda ingin fitur ini di tabel pivot

    protected $table = 'agreement_parking_locations'; // Pastikan ini sudah benar

    protected $fillable = [
        'agreement_id',
        'parking_location_id',
        'assigned_date',
        'removed_date',
        'status',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'removed_date' => 'date',
    ];

    // Relasi ke Agreement (opsional, karena pivot sudah tahu relasinya)
    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    // Relasi ke parkingLocation (opsional, karena pivot sudah tahu relasinya)
    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }
}
