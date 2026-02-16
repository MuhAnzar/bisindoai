<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    protected $table = 'hasil_kuis';
    protected $fillable = ['pengguna_id', 'kuis_id', 'skor', 'total_benar', 'tanggal_dikerjakan'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}
