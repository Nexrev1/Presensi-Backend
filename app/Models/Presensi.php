<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'tanggal',
        'masuk',
        'pulang',
        'ip_address',
        'user_agent',
        'location',
    ];

    // Definisikan relasi dengan User jika perlu
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
