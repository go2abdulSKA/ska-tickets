<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department',
        'short_name',
        'prefix',
        'form_name',
        'logo_path',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Add accessor for 'name' to map to 'department'
    public function getNameAttribute()
    {
        return $this->department;
    }

    // Add accessor for 'dept_name' to map to 'department' (for backward compatibility)
    public function getDeptNameAttribute()
    {
        return $this->department;
    }

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function costCenters()
    {
        return $this->hasMany(CostCenter::class);
    }

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class);
    }

    public function tickets()
    {
        return $this->hasMany(TicketMaster::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
