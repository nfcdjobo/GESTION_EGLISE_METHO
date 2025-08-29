<?php

namespace App\Policies;

// =================================================================
// app/Policies/FimecoPolicy.php


use App\Models\Fimeco;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FimecoPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent voir les FIMECO
    }

    public function view(User $user, Fimeco $fimeco): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'responsable_fimeco']);
    }

    public function update(User $user, Fimeco $fimeco): bool
    {
        return $user->hasRole(['admin']) ||
               $user->id === $fimeco->responsable_id;
    }

    public function delete(User $user, Fimeco $fimeco): bool
    {
        return $user->hasRole(['admin']) &&
               $fimeco->subscriptions()->count() === 0;
    }

    public function manage(User $user): bool
    {
        return $user->hasRole(['admin', 'responsable_fimeco']);
    }
}

