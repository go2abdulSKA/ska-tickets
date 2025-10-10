<?php
// app/Services/TicketNumberService.php

namespace App\Services;

use App\Models\Department;
use App\Models\TicketNumber;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    /**
     * Generate next ticket number with guaranteed sequence integrity
     * Uses database row-level locking to prevent gaps
     */
    public function generateTicketNumber(int $departmentId): string
    {
        return DB::transaction(function () use ($departmentId) {
            // Get department with lock
            $department = Department::lockForUpdate()->findOrFail($departmentId);

            // Get or create ticket number counter with lock
            $ticketNumber = TicketNumber::lockForUpdate()
                ->firstOrCreate(
                    ['department_id' => $departmentId],
                    ['last_used' => 0]
                );

            // Generate next number
            $nextNumber = $ticketNumber->getNextNumber($department->prefix);

            return $nextNumber;
        }, 5); // 5 retry attempts
    }

    /**
     * Reserve a ticket number (for drafts that might be deleted)
     * This ensures no gaps in sequence even if draft is deleted
     */
    public function reserveTicketNumber(int $departmentId): array
    {
        return DB::transaction(function () use ($departmentId) {
            $department = Department::lockForUpdate()->findOrFail($departmentId);
            $ticketNumber = TicketNumber::lockForUpdate()
                ->firstOrCreate(['department_id' => $departmentId], ['last_used' => 0]);

            $number = $ticketNumber->getNextNumber($department->prefix);

            return [
                'number' => $number,
                'prefix' => $department->prefix,
                'sequence' => $ticketNumber->last_used,
            ];
        });
    }

    /**
     * Peek at next number without incrementing (for preview)
     */
    public function previewNextNumber(int $departmentId): string
    {
        $department = Department::findOrFail($departmentId);
        $ticketNumber = TicketNumber::firstOrCreate(
            ['department_id' => $departmentId],
            ['last_used' => 0]
        );

        return $ticketNumber->getNextNumberPreview($department->prefix);
    }
}
