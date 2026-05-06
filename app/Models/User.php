<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Sppg;
use App\Models\Kecamatan;

class User extends Authenticatable
{
    use HasFactory, Notifiable;  // <-- hapus SoftDeletes

    protected $primaryKey = 'id_users';
    protected $fillable = [
        'username', 
        'password', 
        'role', 
        'akses_tipe_usaha', 
        'id_kecamatan'
    ];
    protected $hidden = ['password'];
    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isAdminDinkes() : bool
    {
        return $this->role === 'admin_dinkes';
    }

    public function isAdminKecamatan() : bool
    {
        return $this->role === 'admin_kecamatan';
    }
    public function sppg(): HasOne
    {
        return $this->hasOne(Sppg::class, 'id_users', 'id_users');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}