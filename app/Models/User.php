<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Se elimina 'role' de aquí, ya que ahora es una relación 
     * Many-to-Many en la tabla pivote role_user.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* RELACIONES Y ROLES                                */
    /* -------------------------------------------------------------------------- */

    /**
     * Relación con los roles del usuario.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Verifica si el usuario tiene un rol específico por su nombre (slug).
     * Ejemplo: $user->hasRole('admin');
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Verifica si el usuario tiene un permiso específico a través de cualquiera de sus roles.
     * Ejemplo: $user->hasPermission('edit_roles');
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles->map->permissions->flatten()->contains('name', $permission);
    }
}