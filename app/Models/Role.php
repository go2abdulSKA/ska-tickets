<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role Model
 *
 * Represents user roles in the system
 * Three roles: user, admin, super_admin
 *
 * @package App\Models
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all users with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all permissions for this role
     * Many-to-many relationship
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Get role by name
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()
            ->whereIn('name', $permissions)
            ->exists();
    }

    /**
     * Assign permission to role
     */
    public function givePermission($permission): void
    {
        $permissionId = $permission instanceof Permission
            ? $permission->id
            : $permission;

        $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    /**
     * Remove permission from role
     */
    public function revokePermission($permission): void
    {
        $permissionId = $permission instanceof Permission
            ? $permission->id
            : $permission;

        $this->permissions()->detach($permissionId);
    }

    /**
     * Sync all permissions for this role
     */
    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Check if this is the user role
     */
    public function isUser(): bool
    {
        return $this->name === 'user';
    }

    /**
     * Check if this is an admin role
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    /**
     * Check if this is a super admin role
     */
    public function isSuperAdmin(): bool
    {
        return $this->name === 'super_admin';
    }
}

