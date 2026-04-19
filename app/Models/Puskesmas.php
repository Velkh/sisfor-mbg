<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Puskesmas extends Model
{
    use HasFactory;

    protected $table = 'puskesmas';

    protected $primaryKey = 'id_puskesmas';

    protected $fillable = [
        'nama_puskesmas',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Satu Puskesmas memiliki banyak SPPG
     */
    public function sppg(): HasMany
    {
        return $this->hasMany(Sppg::class, 'id_puskesmas', 'id_puskesmas');
    }

    /**
     * Relasi: Satu Puskesmas memiliki banyak Laporan Penerima
     */
    public function laporanPenerima(): HasMany
    {
        return $this->hasMany(LaporanPenerima::class, 'id_puskesmas', 'id_puskesmas');
    }
}