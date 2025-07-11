<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'event_type',
        'old_value',
        'new_value',
        'changed_by_user_id',
        'notes',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    // Relasi ke Agreement
    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    // Relasi ke User changed
    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
