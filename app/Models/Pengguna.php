<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi mass-assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'email_terverifikasi_pada',
        'foto_profil',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    /**
     * Casting kolom.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_terverifikasi_pada' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }

    /**
     * Dapatkan nama kolom password untuk autentikasi.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }

    public function getHighestScoreAttribute()
    {
        return $this->hasilKuis()->max('skor') ?? 0;
    }
}
