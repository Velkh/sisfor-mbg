<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\sppg;

class User extends Authenticatable
{
    use HasFactory, Notifiable;  // <-- hapus SoftDeletes

    protected $primaryKey = 'id_users';
    protected $fillable = ['username', 'password', 'role', 'nomer_telepon', 'status'];
    protected $hidden = ['password'];
    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isAdminDinkes()
    {
        return $this->role === 'admin_dinkes';
    }

    public function isOperatorSppg()
    {
        return $this->role === 'operator_sppg';
    }
}