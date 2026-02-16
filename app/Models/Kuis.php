<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';
    protected $fillable = ['judul', 'deskripsi', 'gambar_sampul'];

    public function pertanyaans()
    {
        return $this->hasMany(Pertanyaan::class);
    }

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }
}
