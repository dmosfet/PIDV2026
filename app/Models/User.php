<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{

    use Notifiable;
    use HasFactory;
    use TwoFactorAuthenticatable;

    protected $table = 'users';
    protected $connection = 'mysql';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'employee_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'string',
            'employee_id' => 'integer',
            'two_factor_secret' => 'string',
            'two_factor_recovery_codes' => 'string',
            'two_factor_confirmed_at' => 'datetime',
            'remember_token' => 'string',
        ];
    }

    // Eager loading
    protected $with = ['roles.permissions', 'employee'];

    /**
     * Roles de l'utilisateur
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Employé associé
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id','employee_id');
    }


    // ===== GESTION DES PERMISSIONS =====

    /**
     * Vérifie si l'utilisateur a une permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur a un rôle
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Vérifie si l'utilisateur a au moins un des rôles
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    /**
     * Attribue un rôle
     */
    public function assignRole(Role|string $role): self
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);
        $this->load('roles.permissions');

        return $this;
    }

    /**
     * Attribue plusieurs rôles
     */
    public function assignRoles(array $roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('name', $role)->firstOrFail()->id;
            }
            return $role instanceof Role ? $role->id : $role;
        })->toArray();

        $this->roles()->syncWithoutDetaching($roleIds);
        $this->load('roles.permissions');

        return $this;
    }

    /**
     * Synchronise les rôles
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('name', $role)->firstOrFail()->id;
            }
            return $role instanceof Role ? $role->id : $role;
        })->toArray();

        $this->roles()->sync($roleIds);
        $this->load('roles.permissions');

        return $this;
    }

    /**
     * Helpers
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Permet de remplacer le name du user par le nom complet de l'employé qui y est lié.
     * @return Attribute
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->employee ? $this->employee->name : $this->name
        );
    }

    /**
     * Permet de générer les initiales de l'user ou de l'employe qui y est lié.
     * @return Attribute
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->employee ? $this->employee->initials : strtoupper(substr($this->name, 0, 1))
        );
    }
}
