<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    protected $primaryKey = 'id_kecamatan';

    protected $fillable = [
        'nama_kecamatan',
    ];

    protected $casts = [
        'nama_kecamatan' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kelurahan(): HasMany
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function laporanPenerima(): HasMany
    {
        return $this->hasMany(LaporanPenerima::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function sppg(): HasMany
    {
        return $this->hasMany(Sppg::class, 'id_kecamatan', 'id_kecamatan');
    }
}