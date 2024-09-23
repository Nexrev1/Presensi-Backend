<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Config
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @mixin \Eloquent
 */
class Config extends Model
{
    // Menentukan nama tabel jika berbeda dari nama model yang disarankan
    protected $table = 'config'; 

    // Menentukan atribut yang dapat diisi secara massal
    protected $fillable = [
        'latitude',
        'longitude',
        'radius',
    ];

    // Menentukan atribut yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'integer',
    ];
}
