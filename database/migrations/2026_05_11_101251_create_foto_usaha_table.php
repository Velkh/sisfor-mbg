<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_unit_usaha', function (Blueprint $table) {
            $table->id('id_foto'); 
            $table->unsignedBigInteger('id_unit_usaha'); 
            $table->string('foto_unit_usaha'); 
            $table->timestamps(); 

            $table->foreign('id_unit_usaha')
                  ->references('id_unit_usaha')
                  ->on('unit_usahas')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_unit_usaha');
    }
};