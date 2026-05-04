<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kecamatan')->nullable()->after('akses_tipe_usaha');

            $table->foreign('id_kecamatan')
                ->references('id_kecamatan')
                ->on('kecamatan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_kecamatan']);
            $table->dropColumn('id_kecamatan');
        });
    }
};