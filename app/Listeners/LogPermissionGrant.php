<?php
// app/Listeners/LogPermissionGrant.php

namespace App\Listeners;

use App\Models\PermissionAuditLog;

class LogPermissionGrant
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        PermissionAuditLog::create([
            'action' => 'permission_granted_event',
            'model_type' => 'Permission',
            'model_id' => $event->permission->id,
            'user_id' => $event->grantedBy?->id ?? auth()->id(),
            'target_user_id' => $event->user->id,
            'changes' => [
                'permission_name' => $event->permission->name,
                'expires_at' => $event->expiresAt,
                'reason' => $event->reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
