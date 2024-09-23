<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Config
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @mixin \Eloquent
 */
	class Config extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Izin
 *
 * @property int $id
 * @property int $user_id
 * @property string $jenis_izin
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property string|null $dokumen
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read mixed $dokumen_url
 * @property-read mixed $lama_waktu
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Izin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Izin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Izin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereJenisIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Izin whereUserId($value)
 */
	class Izin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Presensi
 *
 * @property int $id
 * @property int $user_id
 * @property string $latitude
 * @property string $longitude
 * @property string $tanggal
 * @property string $masuk
 * @property string|null $pulang
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi wherePulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presensi whereUserId($value)
 */
	class Presensi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $nik
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

