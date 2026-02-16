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
        Schema::create('abjads', function (Blueprint $table) {
            $table->id();
            $table->char('huruf', 1); // A-Z
            $table->string('deskripsi')->nullable();
            $table->string('berkas_video')->nullable(); // path ke video isyarat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abjads');
    }
};
