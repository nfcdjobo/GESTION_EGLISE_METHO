<?php

namespace App\Policies;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnnoncePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les membres connectés peuvent voir les annonces
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Annonce $annonce): bool
    {
        // Les annonces publiées sont visibles par tous
        if ($annonce->statut === 'publiee') {
            return true;
        }

        // Les brouillons ne sont visibles que par l'auteur et les administrateurs
        return $user->id === $annonce->cree_par ||
               $this->isAdmin($user) ||
               $this->isResponsableAnnonces($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Les membres actifs peuvent créer des annonces
        return $user->est_actif && (
            $this->isAdmin($user) ||
            $this->isResponsableAnnonces($user) ||
            $this->isMembre($user)
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Annonce $annonce): Response
    {
        // Les admins peuvent tout modifier
        if ($this->isAdmin($user) || $this->isResponsableAnnonces($user)) {
            return Response::allow();
        }

        // L'auteur peut modifier ses propres annonces non publiées
        if ($user->id === $annonce->cree_par && $annonce->statut === 'brouillon') {
            return Response::allow();
        }

        // L'auteur peut modifier certains champs même après publication
        if ($user->id === $annonce->cree_par && $annonce->statut === 'publiee') {
            return Response::allow('Modification limitée autorisée');
        }

        return Response::deny('Vous n\'êtes pas autorisé à modifier cette annonce.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Annonce $annonce): Response
    {
        // Les admins peuvent tout supprimer
        if ($this->isAdmin($user) || $this->isResponsableAnnonces($user)) {
            return Response::allow();
        }

        // L'auteur peut supprimer ses propres brouillons
        if ($user->id === $annonce->cree_par && $annonce->statut === 'brouillon') {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez pas supprimer cette annonce.');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Annonce $annonce): Response
    {
        // Seuls les admins et responsables peuvent publier
        if (!($this->isAdmin($user) || $this->isResponsableAnnonces($user))) {
            return Response::deny('Vous n\'êtes pas autorisé à publier des annonces.');
        }

        // On ne peut publier que les brouillons
        if ($annonce->statut !== 'brouillon') {
            return Response::deny('Cette annonce n\'est pas dans un état permettant la publication.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, Annonce $annonce): Response
    {
        // Admins et responsables peuvent archiver
        if ($this->isAdmin($user) || $this->isResponsableAnnonces($user)) {
            return Response::allow();
        }

        // L'auteur peut archiver ses propres annonces publiées
        if ($user->id === $annonce->cree_par && $annonce->statut === 'publiee') {
            return Response::allow();
        }

        return Response::deny('Vous n\'êtes pas autorisé à archiver cette annonce.');
    }

    /**
     * Determine whether the user can duplicate the model.
     */
    public function duplicate(User $user, Annonce $annonce): bool
    {
        // Tous les membres autorisés à créer peuvent dupliquer
        return $this->create($user);
    }

    /**
     * Determine whether the user can manage urgent announcements.
     */
    public function manageUrgent(User $user): bool
    {
        return $this->isAdmin($user) || $this->isResponsableAnnonces($user);
    }

    /**
     * Determine whether the user can create announcements for worship service.
     */
    public function createForWorship(User $user): bool
    {
        return $this->isAdmin($user) ||
               $this->isResponsableAnnonces($user) ||
               $this->isResponsableCulte($user);
    }

    // Méthodes helper pour vérifier les rôles
    private function isAdmin(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super_admin');
    }

    private function isResponsableAnnonces(User $user): bool
    {
        return $user->hasRole('responsable_communication') ||
               $user->hasRole('secretaire');
    }

    private function isResponsableCulte(User $user): bool
    {
        return $user->hasRole('pasteur') ||
               $user->hasRole('ancien') ||
               $user->hasRole('responsable_culte');
    }

    private function isMembre(User $user): bool
    {
        return $user->hasRole('membre') ||
               $user->hasRole('membre_actif');
    }
}
