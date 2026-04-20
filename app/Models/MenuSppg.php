<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuSppg extends Model
{
    use HasFactory;

    protected $table = 'menusppg';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_sppg',
        'nama_menu',
        'foto_menu',
    ];

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class, 'id_sppg', 'id_sppg');
    }
}