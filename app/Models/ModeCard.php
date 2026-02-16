<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModeCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'mode_key',
        'title',
        'description',
        'badge_text',
        'badge_emoji',
        'gradient_from',
        'gradient_to',
        'icon_type',
        'icon_content',
        'image',
        'features',
        'button_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the gradient CSS string
     */
    public function getGradientStyleAttribute(): string
    {
        return "background: linear-gradient(to bottom right, {$this->gradient_from}, {$this->gradient_to});";
    }

    /**
     * Get the icon HTML based on type
     */
    public function getIconHtmlAttribute(): string
    {
        if ($this->icon_type === 'letter') {
            return '<span class="text-4xl font-black">' . ($this->icon_content ?? strtoupper(substr($this->mode_key, 0, 1))) . '</span>';
        } elseif ($this->icon_type === 'image' && $this->image) {
            return '<img src="' . asset('storage/' . $this->image) . '" alt="' . $this->title . '" class="w-12 h-12 object-contain">';
        } else {
            return $this->icon_content ?? '';
        }
    }

    /**
     * Scope for active cards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered cards
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get default cards data for seeding
     */
    public static function getDefaults(): array
    {
        return [
            [
                'mode_key' => 'abjad',
                'title' => 'Latihan Abjad',
                'description' => 'Berlatih mengeja kata huruf demi huruf. Sistem AI akan memandu Anda menyelesaikan satu kata penuh dengan akurat.',
                'badge_text' => 'POPULER',
                'badge_emoji' => '✨',
                'gradient_from' => '#14b8a6',
                'gradient_to' => '#10b981',
                'icon_type' => 'letter',
                'icon_content' => 'A',
                'features' => [
                    'Deteksi real-time dengan akurasi tinggi',
                    'Mendukung 1-2 tangan sekaligus',
                    'Feedback visual interaktif'
                ],
                'button_text' => 'Mulai Latihan',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'mode_key' => 'kata',
                'title' => 'Latihan Kata',
                'description' => 'Berlatih bahasa isyarat per kata secara langsung dengan deteksi yang akurat.',
                'badge_text' => 'TERBARU',
                'badge_emoji' => '🔥',
                'gradient_from' => '#6366f1',
                'gradient_to' => '#3b82f6',
                'icon_type' => 'svg',
                'icon_content' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>',
                'features' => [
                    'Deteksi kata dinamis',
                    'Kamus kata lengkap BISINDO'
                ],
                'button_text' => 'Mulai Latihan',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'mode_key' => 'kalimat',
                'title' => 'Latihan Kalimat',
                'description' => 'Bentuk kalimat lengkap dengan kombinasi kata dan abjad. Pilih kata target dan input abjad untuk membentuk kalimat utuh.',
                'badge_text' => 'BARU',
                'badge_emoji' => '🎯',
                'gradient_from' => '#a855f7',
                'gradient_to' => '#ec4899',
                'icon_type' => 'svg',
                'icon_content' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'features' => [
                    'Kombinasi kata dan abjad',
                    'Pilih kata target yang diinginkan',
                    'Gabungkan hasil menjadi kalimat'
                ],
                'button_text' => 'Mulai Latihan',
                'order' => 3,
                'is_active' => true,
            ],
        ];
    }
}
