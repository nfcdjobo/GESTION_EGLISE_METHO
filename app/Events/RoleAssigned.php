<?php
// app/Events/RoleAssigned.php

namespace App\Events;

use App\Models\User;
use App\Models\Role;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $role;
    public $assignedBy;
    public $expiresAt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Role $role, ?User $assignedBy = null, $expiresAt = null)
    {
        $this->user = $user;
        $this->role = $role;
        $this->assignedBy = $assignedBy;
        $this->expiresAt = $expiresAt;
    }
}
