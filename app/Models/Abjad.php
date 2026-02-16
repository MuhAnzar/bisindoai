<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abjad extends Model
{
    protected $fillable = [
        'huruf',
        'deskripsi',
        'berkas_video',
    ];

    /**
     * Get the practice sessions for this abjad.
     */
    public function practices()
    {
        return $this->hasMany(PracticeSession::class, 'word', 'huruf');
    }
}
