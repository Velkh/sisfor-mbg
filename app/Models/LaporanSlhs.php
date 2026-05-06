<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanSlhs extends Model
{
    protected $table = 'laporan_slhs';
    protected $primaryKey = 'id_laporan_slhs';

    protected $fillable = [
        'id_unit_usaha',
        'status_ikl',
        'nilai_ikl',
        'hasil_ikl',
        'status_slhs',
        'tgl_terbit_slhs',
        'tgl_berakhir_slhs',
        'link_slhs',
        'ketersediaan_ipal',
        'jenis_ipal',
        'pengelolaan_sampah',
        'jenis_pengelolaan',
    ];

    protected function casts(): array
    {
        return [
            'status_ikl' => 'string',
            'nilai_ikl' => 'integer',
            'hasil_ikl' => 'string',
            'status_slhs' => 'string',
            'tgl_terbit_slhs' => 'date',
            'tgl_berakhir_slhs' => 'date',
            'ketersediaan_ipal' => 'string',
            'pengelolaan_sampah' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function unitUsaha(): BelongsTo
    {
        return $this->belongsTo(UnitUsaha::class, 'id_unit_usaha', 'id_unit_usaha');
    }
}