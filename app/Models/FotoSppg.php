<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoSppg extends Model
{
    use HasFactory;

    protected $table = 'fotosppg';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_sppg',
        'foto_sppg',
    ];

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class, 'id_sppg', 'id_sppg');
    }
}