<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin';

    protected $fillable = [
        'user_id',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'dokumen',
        'status',
    ];

    // Timestamps enabled by default; remove this if not needed
    public $timestamps = true;

    // Casting attributes
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Accessor for formatted document URL
    public function getDokumenUrlAttribute()
    {
        return $this->dokumen ? asset('storage/' . $this->dokumen) : null;
    }

    // Accessor for lama_waktu
    public function getLamaWaktuAttribute()
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return Carbon::parse($this->tanggal_mulai)->diffInDays(Carbon::parse($this->tanggal_selesai)) + 1;
        }
        return null;
    }

    /**
     * Define relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
