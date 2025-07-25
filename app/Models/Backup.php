<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_size',
        'created_by_user_id',
    ];

    /**
     * Dapatkan pengguna yang membuat backup ini.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Accessor untuk mendapatkan ukuran file dalam format yang mudah dibaca.
     *
     * @param  int  $value
     * @return string
     */
    public function getReadableSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
