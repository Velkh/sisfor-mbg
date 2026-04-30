<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sppg extends Model
{
    use HasFactory;

    protected $table = 'sppg';

    protected $primaryKey = 'id_sppg';

    protected $fillable = [
        'id_users',
        'nama_sppg',
        'nama_kepala',
        'foto_kepala',
        'nama_mitra',
        'jml_pegawai',
        'kapasitas_porsi',
        'id_puskesmas',
        'id_kecamatan',
        'id_kelurahan',
        'status_ikl',
        'nilai_ikl',
        'hasil_ikl',
        'tanggal_ikl',
        'status_slhs',
        'foto_slhs',
        'tgl_berlaku',
        'tgl_berakhir',
    ];

    protected $casts = [
        'status_ikl' => 'string',
        'hasil_ikl' => 'string',
        'tanggal_ikl' => 'datetime',
        'status_slhs' => 'string',
        'tgl_berlaku' => 'datetime',
        'tgl_berakhir' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Satu SPPG milik satu User (Operator)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    /**
     * Relasi: Satu SPPG milik satu Puskesmas
     */
    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'id_puskesmas', 'id_puskesmas');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    /**
     * Relasi: Satu SPPG memiliki banyak Laporan Penerima
     */
    public function laporanPenerimas(): HasMany
    {
        return $this->hasMany(LaporanPenerima::class, 'id_sppg', 'id_sppg');
    }

    /**
     * Relasi: Satu SPPG memiliki banyak Menu
     */
    public function menuSppg(): HasMany
    {
        return $this->hasMany(MenuSppg::class, 'id_sppg', 'id_sppg');
    }

    /**
     * Relasi: Satu SPPG memiliki banyak Foto
     */
    public function fotoSppg(): HasMany
    {
        return $this->hasMany(FotoSppg::class, 'id_sppg', 'id_sppg');
    }
}