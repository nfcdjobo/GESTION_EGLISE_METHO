<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ProjetController;

/*
|--------------------------------------------------------------------------
| Routes des Projets
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des projets de l'église
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('projets')->name('private.projets.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Affiche la liste des projets avec filtrage et pagination
    Route::get('/', [ProjetController::class, 'index'])->middleware('permission:projets.read')->name('index');

    // Affiche le formulaire de création d'un projet
    Route::get('/create', [ProjetController::class, 'create'])->middleware('permission:projets.create')->name('create');

    //  Crée un nouveau projet
    Route::post('/', [ProjetController::class, 'store'])->middleware('permission:projets.create')->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [ProjetController::class, 'statistiques'])->middleware('cache.headers:public;max_age=3600')->middleware('permission:projets.statistics')->name('statistiques.cached');

    // Retourne les statistiques des projets
    Route::get('/statistiques', [ProjetController::class, 'statistiques'])->middleware('permission:projets.statistics')->name('statistiques');

    //  Retourne les projets publics ouverts aux dons
    Route::get('/publics', [ProjetController::class, 'projetsPublics'])->middleware('permission:projets.public')->name('publics');

    // Retourne les options de sélection pour les formulaires
    Route::get('/options', [ProjetController::class, 'options'])->middleware('permission:projets.read')->name('options');

    // Affiche les détails d'un projet spécifique
    Route::get('/{projet}', [ProjetController::class, 'show'])->middleware('permission:projets.read')->name('show');

    // Affiche le formulaire d'édition d'un projet
    Route::get('/{projet}/edit', [ProjetController::class, 'edit'])->middleware('permission:projets.update')->name('edit');

    // Met à jour un projet existant
    Route::put('/{projet}', [ProjetController::class, 'update'])->middleware('permission:projets.update')->name('update');

    // Supprime un projet (soft delete)
    Route::delete('/{projet}', [ProjetController::class, 'destroy'])->middleware('permission:projets.delete')->name('destroy');

    // Affiche les détails d'un projet spécifique
    Route::get('/{projet}/strict', [ProjetController::class, 'show'])->where('projet', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->middleware('permission:projets.read')->name('show.strict');

    // Approuve un projet
    Route::post('/{projet}/approuver', [ProjetController::class, 'approuver'])->middleware('permission:projets.approve')->name('approuver');

    // Planifie un projet (passage de conception à planification)
    Route::post('/{projet}/planifier', [ProjetController::class, 'planifier'])->middleware('permission:projets.plan')->name('planifier');

    // Met un projet en recherche de financement
    Route::post('/{projet}/rechercher-financement', [ProjetController::class, 'rechercherFinancement'])->middleware('permission:projets.seek-funding')->name('rechercher-financement'); // NOUVELLE LIGNE

    // Démarre un projet
    Route::post('/{projet}/demarrer', [ProjetController::class, 'demarrer'])->middleware('permission:projets.start')->name('demarrer');

    // Suspend un projet
    Route::post('/{projet}/suspendre', [ProjetController::class, 'suspendre'])->middleware('permission:projets.suspend')->name('suspendre');

    // Reprend un projet suspendu
    Route::post('/{projet}/reprendre', [ProjetController::class, 'reprendre'])->middleware('permission:projets.resume')->name('reprendre');

    // Termine un projet
    Route::post('/{projet}/terminer', [ProjetController::class, 'terminer'])->middleware('permission:projets.complete')->name('terminer');

    // Annule un projet
    Route::post('/{projet}/annuler', [ProjetController::class, 'annuler'])->middleware('permission:projets.cancel')->name('annuler');

    // Mise à jour de la progression
    Route::post('/{projet}/progression', [ProjetController::class, 'mettreAJourProgression'])->middleware('permission:projets.update-progress')->name('progression');

    //  Met un projet en attente (prêt à démarrer)
    Route::post('/{projet}/mettre-en-attente', [ProjetController::class, 'mettreEnAttente'])->middleware('permission:projets.put-on-hold')->name('mettre-en-attente');

    // Duplication de projet
    Route::post('/{projet}/dupliquer', [ProjetController::class, 'dupliquer'])->middleware('permission:projets.duplicate')->name('dupliquer');

    // Upload d'image pour un projet
    Route::post('/{projet}/upload-image', [ProjetController::class, 'uploadImage'])->middleware('permission:projets.upload-images')->name('upload.image');

    // Route pour forcer la mise en attente (bypass financement)
    Route::post('/{projet}/forcer-attente', [ProjetController::class, 'forcerMiseEnAttente'])->middleware('permission:projets.put-on-hold')->name('forcer-attente');

    // Route générique pour exécuter n'importe quelle action du workflow
    Route::post('/{projet}/action/{action}', [ProjetController::class, 'executerAction'])->middleware('permission:projets.update')->name('executer-action');

    // Route pour obtenir le statut détaillé et le workflow possible
    Route::get('/{projet}/statut', [ProjetController::class, 'getStatutDetaille'])->middleware('permission:projets.read')->name('statut-detaille');

    // Route pour valider le workflow (existante - améliorée)
    Route::get('/{projet}/workflow', [ProjetController::class, 'validerWorkflow'])->middleware('permission:projets.read')->name('workflow');

});






