<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_slhs', function (Blueprint $table) {
            $table->id('id_laporan_slhs');
            $table->unsignedBigInteger('id_unit_usaha')->unique();

            $table->enum('status_ikl', ['belum_mengajukan', 'sudah_mengajukan', 'selesai'])->nullable();
            $table->integer('nilai_ikl')->nullable();
            $table->enum('hasil_ikl', ['memenuhi', 'tidak_memenuhi'])->nullable();

            $table->enum('status_slhs', ['belum_mengajukan', 'sudah_mengajukan', 'selesai'])->nullable();
            $table->date('tgl_terbit_slhs')->nullable();
            $table->date('tgl_berakhir_slhs')->nullable();
            $table->string('link_slhs')->nullable();

            $table->enum('ketersediaan_ipal', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->string('jenis_ipal')->nullable();

            $table->enum('pengelolaan_sampah', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->string('jenis_pengelolaan')->nullable();

            $table->timestamps();

            $table->foreign('id_unit_usaha')
                ->references('id_unit_usaha')
                ->on('unit_usahas')
                ->cascadeOnDelete();

            $table->index('tgl_berakhir_slhs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_slhs');
    }
};