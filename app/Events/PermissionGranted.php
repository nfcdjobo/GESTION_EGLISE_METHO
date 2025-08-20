<?php
// app/Events/PermissionGranted.php

namespace App\Events;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionGranted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $permission;
    public $grantedBy;
    public $expiresAt;
    public $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Permission $permission, ?User $grantedBy = null, $expiresAt = null, $reason = null)
    {
        $this->user = $user;
        $this->permission = $permission;
        $this->grantedBy = $grantedBy;
        $this->expiresAt = $expiresAt;
        $this->reason = $reason;
    }
}
