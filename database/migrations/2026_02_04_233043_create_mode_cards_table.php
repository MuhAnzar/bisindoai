<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mode_cards', function (Blueprint $table) {
            $table->id();
            $table->string('mode_key')->unique(); // 'abjad', 'kata', 'kalimat'
            $table->string('title');
            $table->text('description');
            $table->string('badge_text');
            $table->string('badge_emoji')->default('✨');
            $table->string('gradient_from')->default('#57BBA0'); // Color hex
            $table->string('gradient_to')->default('#45A38A'); // Color hex
            $table->string('icon_type')->default('letter'); // 'letter', 'svg', 'image'
            $table->text('icon_content')->nullable(); // letter character or SVG path
            $table->string('image')->nullable(); // custom image path
            $table->json('features'); // array of feature strings
            $table->string('button_text')->default('Mulai Latihan');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_cards');
    }
};
