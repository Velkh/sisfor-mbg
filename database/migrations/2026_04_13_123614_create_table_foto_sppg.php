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
        Schema::create('fotosppg', function (Blueprint $table) {
            $table->id('id_foto');
            $table->unsignedBigInteger('id_sppg');
            $table->string('foto_sppg');
            $table->timestamps();

            $table->foreign('id_sppg')->references('id_sppg')->on('sppg')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotosppg');
    }
};
