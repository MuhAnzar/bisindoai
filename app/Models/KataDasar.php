<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KataDasar extends Model
{
    protected $fillable = [
        'kata',
        'kategori',
        'arti',
        'berkas_video',
    ];

    /**
     * Get the practice sessions for this kata dasar.
     */
    public function practices()
    {
        return $this->hasMany(PracticeSession::class, 'word', 'kata');
    }
}
