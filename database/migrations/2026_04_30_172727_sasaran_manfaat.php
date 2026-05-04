<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sasaran_manfaat', function (Blueprint $table) {
            $table->id('id_sasaran_manfaat');
            $table->unsignedBigInteger('id_unit_usaha');

            $table->enum('kategori', ['Sekolah', 'B3', 'Umum']);
            $table->enum('tipe_instansi', ['TK', 'SD', 'SMP', 'SMA', 'Posyandu', 'TPP', 'DAM', 'Kantin', 'Lainnya'])->nullable();

            $table->string('nama_instansi')->nullable();
            $table->enum('status', ['negeri', 'swasta'])->nullable();

            $table->integer('jumlah_siswa')->nullable();
            $table->integer('jumlah_bumil')->nullable();
            $table->integer('jumlah_busui')->nullable();
            $table->integer('jumlah_balita')->nullable();

            $table->text('detail_jangkauan')->nullable();
            $table->integer('jumlah_jiwa')->default(0);

            $table->timestamps();

            $table->foreign('id_unit_usaha')->references('id_unit_usaha')->on('unit_usahas')->cascadeOnDelete();
            $table->index(['id_unit_usaha', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sasaran_manfaat');
    }
};