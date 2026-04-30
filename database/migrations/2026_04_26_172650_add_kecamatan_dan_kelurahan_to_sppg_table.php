<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sppg', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kecamatan')->nullable()->after('id_puskesmas');
            $table->unsignedBigInteger('id_kelurahan')->nullable()->after('id_kecamatan');

            $table->foreign('id_kecamatan')
                ->references('id_kecamatan')
                ->on('kecamatan')
                ->cascadeOnDelete();

            $table->foreign('id_kelurahan')
                ->references('id_kelurahan')
                ->on('kelurahan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sppg', function (Blueprint $table) {
            $table->dropForeign(['id_kecamatan']);
            $table->dropForeign(['id_kelurahan']);

            $table->dropColumn(['id_kecamatan', 'id_kelurahan']);
        });
    }
};