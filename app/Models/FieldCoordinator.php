<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldCoordinator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'address',
        'id_card_number',
        'id_card_img',
        'phone_number',
    ];

    //relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }
}
