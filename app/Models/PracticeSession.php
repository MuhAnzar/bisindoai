<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'word',
        'duration',
        'accuracy_percentage',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'accuracy_percentage' => 'decimal:2',
    ];

    /**
     * Get the user that owns the practice session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    /**
     * Get formatted duration (MM:SS)
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get accuracy badge class based on percentage
     */
    public function getAccuracyBadgeAttribute(): string
    {
        if ($this->accuracy_percentage >= 80) {
            return 'excellent';
        } elseif ($this->accuracy_percentage >= 60) {
            return 'good';
        } else {
            return 'fair';
        }
    }
}
