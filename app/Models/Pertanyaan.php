<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $fillable = ['kuis_id', 'pertanyaan', 'media_url', 'tipe_media'];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class);
    }
}
