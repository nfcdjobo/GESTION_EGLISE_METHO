<?php
// app/Listeners/RefreshUserPermissionCache.php

namespace App\Listeners;

use App\Jobs\RefreshPermissionCache;

class RefreshUserPermissionCache
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if (isset($event->user)) {
            dispatch(new RefreshPermissionCache($event->user->id));
        }
    }
}
