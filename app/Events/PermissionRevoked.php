<?php
// app/Events/PermissionRevoked.php

namespace App\Events;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionRevoked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $permission;
    public $revokedBy;
    public $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Permission $permission, ?User $revokedBy = null, $reason = null)
    {
        $this->user = $user;
        $this->permission = $permission;
        $this->revokedBy = $revokedBy;
        $this->reason = $reason;
    }
}
