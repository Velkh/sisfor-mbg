<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            DB::statement("ALTER TABLE unit_usahas MODIFY jenis_usaha VARCHAR(50) NOT NULL");
        });

        // Tambah kolom api_unit_id jika belum ada
        Schema::table('unit_usahas', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_usahas', 'api_unit_id')) {
                $table->unsignedBigInteger('api_unit_id')->nullable()->after('id_unit_usaha');
                $table->index('api_unit_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            DB::statement("ALTER TABLE unit_usahas MODIFY jenis_usaha ENUM('sppg', 'tpp', 'dam', 'kantin') NOT NULL");
        });

        Schema::table('unit_usahas', function (Blueprint $table) {
            if (Schema::hasColumn('unit_usahas', 'api_unit_id')) {
                $table->dropIndex(['api_unit_id']);
                $table->dropColumn('api_unit_id');
            }
        });
    }
};