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
        Schema::create('menusppg', function (Blueprint $table) {
            $table->id('id_menu');
            $table->unsignedBigInteger('id_sppg'); 
            $table->string('nama_menu');
            $table->string('foto_menu')->nullable();
            $table->timestamps();

            $table->foreign('id_sppg')->references('id_sppg')->on('sppg')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menusppg');
    }
};
