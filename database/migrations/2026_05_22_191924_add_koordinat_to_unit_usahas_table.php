<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            // Menggunakan nullable() agar data lama yang tidak punya koordinat tidak error
            $table->string('latitude')->nullable()->after('alamat');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};