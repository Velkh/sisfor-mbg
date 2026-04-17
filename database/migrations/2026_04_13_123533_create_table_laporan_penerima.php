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
        Schema::create('laporanpenerima', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_sppg');
            $table->enum('kategori', ['kategori_a', 'kategori_b']); // Sesuaikan isinya
            $table->enum('tipe_instansi', ['sekolah', 'posyandu']); // Sesuaikan isinya
            $table->string('nama_instansi');
            $table->enum('status', ['menunggu', 'selesai']); // Sesuaikan isinya
            $table->unsignedBigInteger('id_kelurahan');
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_puskesmas');
            $table->integer('jml_siswa')->default(0);
            $table->integer('jml_bumil')->default(0);
            $table->integer('jml_busui')->default(0);
            $table->integer('jml_balita')->default(0);
            $table->timestamps();

            $table->foreign('id_sppg')->references('id_sppg')->on('sppg')->cascadeOnDelete();
            $table->foreign('id_kelurahan')->references('id_kelurahan')->on('kelurahan')->cascadeOnDelete();
            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->cascadeOnDelete();
            $table->foreign('id_puskesmas')->references('id_puskesmas')->on('puskesmas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporanpenerima');
    }
};
