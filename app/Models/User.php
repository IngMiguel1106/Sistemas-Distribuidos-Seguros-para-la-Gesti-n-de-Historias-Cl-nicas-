<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "hcd.login_usuario";
    public $timestamps = false;
protected $primaryKey = 'documento_id';
public $incrementing = false;
protected $keyType = 'string';

    protected $fillable = [
        'nombre_usuario',
        'contrasena',
        'correo',
        'rol_id',
        'documento_id',
        'activo',
        'ultimo_login',
        'creado_en',
        'actualizado_en',
    ];
    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
{
    return $this->contrasena;
}

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_login' => 'datetime',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
        //'password' => 'hashed',  // No usar porque tu campo es "contrasena"
    ];

    /**
     * Métodos requeridos por JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}