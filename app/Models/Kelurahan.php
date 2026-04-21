<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = 'kelurahan';

    protected $primaryKey = 'id_kelurahan';

    protected $fillable = [
        'nama_kelurahan',
        'id_kecamatan',
    ];

    protected $casts = [
        'nama_kelurahan' => 'string',
        'id_kecamatan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function laporanPenerima(): HasMany
    {
        return $this->hasMany(LaporanPenerima::class, 'id_kelurahan', 'id_kelurahan');
    }
}