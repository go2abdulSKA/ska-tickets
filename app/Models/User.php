<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 *
 * Extended Jetstream User with:
 * - Role-based permissions
 * - Multi-department access
 * - Soft deletes
 * - Audit tracking
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'country',
        'profile_photo',
        'profile_photo_path',
        'is_active',
        'two_factor_enabled',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the role that owns the user
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all departments this user belongs to
     * Many-to-many relationship
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_departments')
            ->withTimestamps();
    }

    /**
     * Get all tickets created by this user
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketMaster::class, 'user_id');
    }

    /**
     * Get all clients created by this user
     */
    public function createdClients(): HasMany
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    /**
     * Get the user who created this user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this user
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by role
     */
    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope: Filter by department
     */
    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->whereHas('departments', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        });
    }

    /**
     * Scope: Search by name or email
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->role?->hasAnyPermission($permissions) ?? false;
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role?->name === 'super_admin';
    }

    /**
     * Check if user is admin (admin or super admin)
     */
    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->name, ['admin', 'super_admin']);
    }

    /**
     * Check if user belongs to a specific department
     */
    public function belongsToDepartment(int $departmentId): bool
    {
        return $this->departments()->where('department_id', $departmentId)->exists();
    }

    /**
     * Get user's department IDs as array
     */
    public function getDepartmentIds(): array
    {
        return $this->departments()->pluck('department_id')->toArray();
    }

    /**
     * Get display name (full_name or name)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->name;
    }

    /**
     * Get user initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (strlen($initials) >= 2) break;
        }

        return $initials ?: 'U';
    }
}
