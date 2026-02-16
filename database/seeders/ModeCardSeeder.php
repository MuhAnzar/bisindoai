<?php

namespace Database\Seeders;

use App\Models\ModeCard;
use Illuminate\Database\Seeder;

class ModeCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ModeCard::getDefaults() as $cardData) {
            ModeCard::updateOrCreate(
                ['mode_key' => $cardData['mode_key']],
                $cardData
            );
        }
    }
}
