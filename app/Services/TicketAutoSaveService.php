<?php
// app/Services/TicketAutoSaveService.php

namespace App\Services;

use App\Models\TicketMaster;
use Illuminate\Support\Facades\Cache;

class TicketAutoSaveService
{
    /**
     * Auto-save draft ticket data
     * Stores in cache with 1 hour expiry
     */
    public function saveDraft(int $userId, array $data): void
    {
        $cacheKey = "ticket_draft_{$userId}";
        
        Cache::put($cacheKey, [
            'data' => $data,
            'timestamp' => now(),
        ], 3600); // 1 hour
    }

    /**
     * Retrieve draft data
     */
    public function getDraft(int $userId): ?array
    {
        return Cache::get("ticket_draft_{$userId}");
    }

    /**
     * Clear draft after successful save
     */
    public function clearDraft(int $userId): void
    {
        Cache::forget("ticket_draft_{$userId}");
    }

    /**
     * Check if user has unsaved draft
     */
    public function hasDraft(int $userId): bool
    {
        return Cache::has("ticket_draft_{$userId}");
    }
}
