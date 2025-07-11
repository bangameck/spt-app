<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agreement_id',
        'deposit_date',
        'amount',
        'is_validated',
        'validated_date',
        'notes',
        'created_by_user_id',
    ];

    protected $casts = [
        'deposit_date' => 'date',
        'amount' => 'decimal:2',
        'is_validated' => 'boolean',
        'validation_date' => 'date',
    ];

    // Relasi ke Agreement
    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    // Relasi ke User Creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by_user_id');
    }
}
