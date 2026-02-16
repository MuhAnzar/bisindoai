<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    protected $fillable = ['pertanyaan_id', 'jawaban', 'apakah_benar'];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class);
    }
}
