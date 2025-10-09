<?php

namespace App\Services;

use App\Models\Department;
use App\Models\TicketNumber;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * TicketNumberService
 * 
 * Handles sequential ticket number generation with database locking
 * to prevent duplicate numbers in concurrent environments.
 * 
 * CRITICAL: Uses database transactions with row-level locking
 * to ensure no gaps or duplicates even when multiple users
 * create tickets simultaneously.
 * 
 * @package App\Services
 */
class TicketNumberService
{
    /**
     * Generate next ticket number for a department
     * 
     * Uses database transaction with row locking to ensure sequential numbering
     * 
     * @param int $departmentId
     * @return string Full ticket number (e.g., "C/A-00001")
     * @throws Exception
     */
    public function generateTicketNumber(int $departmentId): string
    {
        try {
            return DB::transaction(function () use ($departmentId) {
                // Get department to retrieve prefix
                $department = Department::findOrFail($departmentId);

                // Lock the ticket number row for this department
                // CRITICAL: Locks this row until transaction completes
                // This prevents race conditions when multiple users create tickets simultaneously
                $ticketNumber = TicketNumber::where('department_id', $departmentId)
                    ->lockForUpdate()
                    ->first();

                // If no record exists for this department, create one
                if (!$ticketNumber) {
                    $ticketNumber = TicketNumber::create([
                        'department_id' => $departmentId,
                        'last_used' => 0,
                        'last_user' => auth()->user()?->name ?? 'System',
                        'is_adding' => false,
                    ]);
                }

                // Generate the next ticket number
                $fullTicketNumber = $ticketNumber->getNextNumber($department->prefix);

                return $fullTicketNumber;
            });
        } catch (Exception $e) {
            // Log error for debugging
            logger()->error('Failed to generate ticket number', [
                'department_id' => $departmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new Exception('Failed to generate ticket number. Please try again.');
        }
    }

    /**
     * Preview next ticket number without saving
     * 
     * Shows what the next ticket number will be
     * Used in forms to show preview before creating ticket
     * 
     * @param int $departmentId
     * @return string Preview of next ticket number
     */
    public function previewNextTicketNumber(int $departmentId): string
    {
        try {
            $department = Department::findOrFail($departmentId);
            $ticketNumber = TicketNumber::where('department_id', $departmentId)->first();

            if (!$ticketNumber) {
                // First ticket for this department
                return $department->prefix . '-00001';
            }

            return $ticketNumber->getNextNumberPreview($department->prefix);
        } catch (Exception $e) {
            logger()->error('Failed to preview ticket number', [
                'department_id' => $departmentId,
                'error' => $e->getMessage(),
            ]);

            return 'Error';
        }
    }

    /**
     * Get current ticket number for a department
     * 
     * Returns the last generated ticket number
     * Returns null if no tickets have been created yet
     * 
     * @param int $departmentId
     * @return string|null Current ticket number or null
     */
    public function getCurrentTicketNumber(int $departmentId): ?string
    {
        try {
            $department = Department::findOrFail($departmentId);
            $ticketNumber = TicketNumber::where('department_id', $departmentId)->first();

            if (!$ticketNumber || $ticketNumber->last_used === 0) {
                return null;
            }

            return $ticketNumber->getCurrentNumber($department->prefix);
        } catch (Exception $e) {
            logger()->error('Failed to get current ticket number', [
                'department_id' => $departmentId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Reset ticket number counter (ADMIN ONLY - USE WITH CAUTION)
     * 
     * Resets the counter to a specific number
     * WARNING: This should only be used by super admins in special circumstances
     * 
     * @param int $departmentId
     * @param int $resetToNumber
     * @return bool Success status
     * @throws Exception
     */
    public function resetCounter(int $departmentId, int $resetToNumber): bool
    {
        try {
            return DB::transaction(function () use ($departmentId, $resetToNumber) {
                $ticketNumber = TicketNumber::where('department_id', $departmentId)
                    ->lockForUpdate()
                    ->first();

                if (!$ticketNumber) {
                    throw new Exception('Ticket number record not found for department');
                }

                // Verify no tickets exist beyond this number
                $maxTicketNo = DB::table('ticket_masters')
                    ->where('department_id', $departmentId)
                    ->selectRaw('MAX(CAST(SUBSTRING_INDEX(ticket_no, "-", -1) AS UNSIGNED)) as max_no')
                    ->value('max_no');

                if ($maxTicketNo && $resetToNumber < $maxTicketNo) {
                    throw new Exception("Cannot reset to {$resetToNumber}. Tickets already exist up to {$maxTicketNo}");
                }

                return $ticketNumber->resetTo($resetToNumber);
            });
        } catch (Exception $e) {
            logger()->error('Failed to reset ticket counter', [
                'department_id' => $departmentId,
                'reset_to' => $resetToNumber,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get ticket statistics for a department
     * 
     * Returns useful stats about ticket numbering
     * 
     * @param int $departmentId
     * @return array Statistics
     */
    public function getStatistics(int $departmentId): array
    {
        try {
            $department = Department::findOrFail($departmentId);
            $ticketNumber = TicketNumber::where('department_id', $departmentId)->first();

            $stats = [
                'department' => $department->department,
                'prefix' => $department->prefix,
                'last_used' => $ticketNumber?->last_used ?? 0,
                'current_ticket' => $this->getCurrentTicketNumber($departmentId),
                'next_ticket' => $this->previewNextTicketNumber($departmentId),
                'last_user' => $ticketNumber?->last_user ?? 'N/A',
                'last_updated' => $ticketNumber?->updated_at?->diffForHumans() ?? 'Never',
            ];

            return $stats;
        } catch (Exception $e) {
            logger()->error('Failed to get ticket statistics', [
                'department_id' => $departmentId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
