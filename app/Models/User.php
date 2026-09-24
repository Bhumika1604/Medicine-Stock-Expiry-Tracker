<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PHARMACIST = 'pharmacist';
    public const ROLE_STAFF = 'staff';

    public const ROLES = [self::ROLE_ADMIN, self::ROLE_PHARMACIST, self::ROLE_STAFF];

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role helpers (added for role-based authorization; existing auth/session
    | behaviour above is unchanged).
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPharmacist(): bool
    {
        return $this->role === self::ROLE_PHARMACIST;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /** Admin + Pharmacist can manage medicines, categories, batches. */
    public function canManageInventory(): bool
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_PHARMACIST]);
    }

    /** Admin + Pharmacist can view/export reports. */
    public function canViewReports(): bool
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_PHARMACIST]);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_PHARMACIST => 'Pharmacist',
            self::ROLE_STAFF => 'Staff',
            default => ucfirst($this->role),
        };
    }
}
