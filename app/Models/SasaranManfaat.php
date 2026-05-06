<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SasaranManfaat extends Model
{
    protected $table = 'sasaran_manfaat';
    protected $primaryKey = 'id_sasaran_manfaat';

    protected $fillable = [
        'id_unit_usaha',
        'kategori',
        'tipe_instansi',
        'nama_instansi',
        'status',
        'jumlah_siswa',
        'jumlah_bumil',
        'jumlah_busui',
        'jumlah_balita',
        'detail_jangkauan',
        'jumlah_jiwa',
    ];

    protected function casts(): array
    {
        return [
            'kategori' => 'string',
            'tipe_instansi' => 'string',
            'status' => 'string',
            'jumlah_siswa' => 'integer',
            'jumlah_bumil' => 'integer',
            'jumlah_busui' => 'integer',
            'jumlah_balita' => 'integer',
            'jumlah_jiwa' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function unitUsaha(): BelongsTo
    {
        return $this->belongsTo(UnitUsaha::class, 'id_unit_usaha', 'id_unit_usaha');
    }

    
}