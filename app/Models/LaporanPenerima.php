<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPenerima extends Model
{
    use HasFactory;

    protected $table = 'laporanpenerima';
    protected $primaryKey = 'id_laporan';

    public const KATEGORI_SATUAN_PENDIDIKAN = 'Satuan Pendidikan';
    public const KATEGORI_KELOMPOK_B3 = 'Kelompok B3';

    public const TIPE_TK = 'TK Sederajat';
    public const TIPE_SD = 'SD Sederajat';
    public const TIPE_SMP = 'SMP Sederajat';
    public const TIPE_SMA = 'SMA Sederajat';
    public const TIPE_POSYANDU = 'Posyandu';

    public const STATUS_NEGERI = 'negeri';
    public const STATUS_SWASTA = 'swasta';

    protected $fillable = [
        'id_sppg',
        'kategori',
        'tipe_instansi',
        'nama_instansi',
        'status',
        'id_kelurahan',
        'id_kecamatan',
        'id_puskesmas',
        'jml_siswa',
        'jml_bumil',
        'jml_busui',
        'jml_balita',
    ];

    protected $casts = [
        'kategori' => 'string',
        'tipe_instansi' => 'string',
        'status' => 'string',
        'jml_siswa' => 'integer',
        'jml_bumil' => 'integer',
        'jml_busui' => 'integer',
        'jml_balita' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function tipeSekolah(): array
    {
        return [
            self::TIPE_TK,
            self::TIPE_SD,
            self::TIPE_SMP,
            self::TIPE_SMA,
        ];
    }

    public function isTipeSekolah(): bool
    {
        return in_array($this->tipe_instansi, self::tipeSekolah(), true);
    }

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class, 'id_sppg', 'id_sppg');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'id_puskesmas', 'id_puskesmas');
    }
}