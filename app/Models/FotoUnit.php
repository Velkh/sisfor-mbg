<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoUnit extends Model
{
    use HasFactory;

    protected $table = 'foto_unit_usaha';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_unit_usaha',
        'foto_unit_usaha',
    ];

    public function unitUsaha(): BelongsTo
    {
        return $this->belongsTo(UnitUsaha::class, 'id_unit_usaha', 'id_unit_usaha');
    }
}