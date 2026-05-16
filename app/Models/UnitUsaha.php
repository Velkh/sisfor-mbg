<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UnitUsaha extends Model
{
    protected $table = 'unit_usahas';
    protected $primaryKey = 'id_unit_usaha';

    protected $fillable = [
        'id_kecamatan',
        'id_kelurahan',
        'id_puskesmas',
        'jenis_usaha',
        'nama_unit_usaha',
        'nama_pemilik',
        'alamat',
        'jumlah_pegawai',
        'jumlah_penjamah_terlatih',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'jenis_usaha' => 'string',
            'jumlah_pegawai' => 'integer',
            'jumlah_penjamah_terlatih' => 'integer',
            'status_aktif' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'id_puskesmas', 'id_puskesmas');
    }

    public function sasaranManfaat(): HasMany
    {
        return $this->hasMany(SasaranManfaat::class, 'id_unit_usaha', 'id_unit_usaha');
    }

    public function laporanSlhs(): HasOne
    {
        return $this->hasOne(LaporanSlhs::class, 'id_unit_usaha', 'id_unit_usaha');
    }

    public function fotos()
    {
        return $this->hasMany(FotoUnit::class, 'id_unit_usaha', 'id_unit_usaha');
    }
}