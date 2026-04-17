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
        Schema::create('sppg', function (Blueprint $table) {
            $table->id('id_sppg');
            $table->unsignedBigInteger('id_users');
            $table->string('nama_sppg');
            $table->string('nama_kepala');
            $table->string('foto_kepala')->nullable();
            $table->string('nama_mitra');
            $table->integer('jml_pegawai');
            $table->integer('kapasitas_porsi');
            $table->unsignedBigInteger('id_puskesmas');
            $table->enum('status_ikl', ['belum_mengajukan', 'sudah_mengajukan', 'selesai']); 
            $table->integer('nilai_ikl');
            $table->enum('hasil_ikl', ['memenuhi', 'tidak_memenuhi']);
            $table->dateTime('tanggal_ikl');
            $table->enum('status_slhs', ['belum_mengajukan', 'sudah_mengajukan', 'selesai']); 
            $table->string('foto_slhs')->nullable();
            $table->dateTime('tgl_berlaku');
            $table->dateTime('tgl_berakhir');
            $table->timestamps();

            $table->foreign('id_users')->references('id_users')->on('users')->cascadeOnDelete();
            $table->foreign('id_puskesmas')->references('id_puskesmas')->on('puskesmas')->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppg');
    }
};
