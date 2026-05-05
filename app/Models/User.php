<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // ========== RELACIONES ==========

    /**
     * Un usuario tiene muchas ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Un usuario registra muchas entradas de inventario
     */
    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }

    /**
     * Un usuario registra muchas salidas de inventario
     */
    public function salidasInventario()
    {
        return $this->hasMany(SalidaInventario::class);
    }

    // ========== SCOPES ==========

    /**
     * Filtrar solo usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Filtrar por rol específico
     */
    public function scopePorRol($query, $rol)
    {
        return $query->where('rol', $rol);
    }

    /**
     * Obtener solo dueños
     */
    public function scopeDuenos($query)
    {
        return $query->where('rol', 'dueno');
    }

    /**
     * Obtener solo encargados
     */
    public function scopeEncargados($query)
    {
        return $query->where('rol', 'encargado');
    }

    /**
     * Obtener solo cajeros
     */
    public function scopeCajeros($query)
    {
        return $query->where('rol', 'cajero');
    }
}
