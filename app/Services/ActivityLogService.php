<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * ActivityLogService
 * 
 * Simplified helper service for logging user activities
 * Automatically tracks user, IP, and request information
 * 
 * @package App\Services
 */
class ActivityLogService
{
    /**
     * Log an activity
     * 
     * @param string $description Human-readable description
     * @param Model|null $subject The model being acted upon
     * @param string|null $event Event type (created, updated, deleted, etc.)
     * @param array|null $properties Additional data (old/new values)
     * @param string|null $logName Category/module name
     * @return ActivityLog
     */
    public function log(
        string $description,
        ?Model $subject = null,
        ?string $event = null,
        ?array $properties = null,
        ?string $logName = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'System',
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'event' => $event,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    /**
     * Log a created event
     * 
     * @param Model $model
     * @param string|null $description
     * @return ActivityLog
     */
    public function logCreated(Model $model, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Created {$modelName}";

        return $this->log(
            description: $desc,
            subject: $model,
            event: 'created',
            properties: ['attributes' => $model->getAttributes()],
            logName: strtolower($modelName)
        );
    }

    /**
     * Log an updated event
     * 
     * @param Model $model
     * @param array $oldValues
     * @param string|null $description
     * @return ActivityLog
     */
    public function logUpdated(Model $model, array $oldValues, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Updated {$modelName}";

        return $this->log(
            description: $desc,
            subject: $model,
            event: 'updated',
            properties: [
                'old' => $oldValues,
                'attributes' => $model->getAttributes(),
            ],
            logName: strtolower($modelName)
        );
    }

    /**
     * Log a deleted event
     * 
     * @param Model $model
     * @param string|null $description
     * @return ActivityLog
     */
    public function logDeleted(Model $model, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Deleted {$modelName}";

        return $this->log(
            description: $desc,
            subject: $model,
            event: 'deleted',
            properties: ['attributes' => $model->getAttributes()],
            logName: strtolower($modelName)
        );
    }

    /**
     * Log a login event
     * 
     * @return ActivityLog
     */
    public function logLogin(): ActivityLog
    {
        return $this->log(
            description: 'User logged in',
            event: 'login',
            logName: 'auth'
        );
    }

    /**
     * Log a logout event
     * 
     * @return ActivityLog
     */
    public function logLogout(): ActivityLog
    {
        return $this->log(
            description: 'User logged out',
            event: 'logout',
            logName: 'auth'
        );
    }

    /**
     * Log a custom event
     * 
     * @param string $description
     * @param string $logName
     * @param array|null $properties
     * @return ActivityLog
     */
    public function logCustom(string $description, string $logName, ?array $properties = null): ActivityLog
    {
        return $this->log(
            description: $description,
            properties: $properties,
            logName: $logName
        );
    }
}
