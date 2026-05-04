<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_usahas', function (Blueprint $table) {
            $table->id('id_unit_usaha');
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_kelurahan');
            $table->unsignedBigInteger('id_puskesmas');
            $table->enum('jenis_usaha', ['sppg', 'tpp', 'dam', 'kantin']);
            $table->string('nama_unit_usaha');
            $table->string('nama_pemilik');
            $table->text('alamat');
            $table->integer('jumlah_pegawai')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->cascadeOnDelete();
            $table->foreign('id_kelurahan')->references('id_kelurahan')->on('kelurahan')->cascadeOnDelete();
            $table->foreign('id_puskesmas')->references('id_puskesmas')->on('puskesmas')->cascadeOnDelete();

            $table->index(['id_kecamatan', 'jenis_usaha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_usahas');
    }
};