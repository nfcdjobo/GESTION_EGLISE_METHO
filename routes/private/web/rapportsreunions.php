<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\RapportReunionController;

/*
|--------------------------------------------------------------------------
| Routes des Rapports de Réunions
|--------------------------------------------------------------------------
|
| Routes pour la gestion des rapports de réunions
| Organisées dans un groupe avec middleware d'authentification
| Utilise uniquement les méthodes disponibles dans le controller
|
*/

Route::prefix('dashboard/rapports-reunions')->name('private.rapports-reunions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // ================================
    // ROUTES CRUD PRINCIPALES
    // ================================

    Route::get('/', [RapportReunionController::class, 'index'])->name('index');
    Route::get('/create', [RapportReunionController::class, 'create'])->name('create');
    // Dans la section STATISTIQUES ET EXPORT, modifiez :
Route::get('/admin/export', [RapportReunionController::class, 'export'])->name('export');
    Route::post('/', [RapportReunionController::class, 'store'])->name('store');

    // Routes avec paramètres de rapport
    Route::get('/{rapport}', [RapportReunionController::class, 'show'])->name('show');
    Route::get('/{rapport}/edit', [RapportReunionController::class, 'edit'])->name('edit');
    Route::put('/{rapport}', [RapportReunionController::class, 'update'])->name('update');
    Route::post('/{rapport}', [RapportReunionController::class, 'update'])->name('update.post');
    Route::delete('/{rapport}', [RapportReunionController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte pour sécurité renforcée
    Route::get('/{rapport}/details', [RapportReunionController::class, 'show'])
        ->where('rapport', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
        ->name('details');

    // ================================
    // WORKFLOW DE VALIDATION
    // ================================

    Route::post('/{rapport}/revision', [RapportReunionController::class, 'passerEnRevision'])->name('revision');
    Route::post('/{rapport}/valider', [RapportReunionController::class, 'valider'])->name('valider');
    Route::post('/{rapport}/publier', [RapportReunionController::class, 'publier'])->name('publier');
    Route::post('/{rapport}/rejeter', [RapportReunionController::class, 'rejeter'])->name('rejeter');

    // ================================
    // GESTION DES PRÉSENCES
    // ================================

    Route::post('/{rapport}/presences', [RapportReunionController::class, 'ajouterPresence'])->name('presences.ajouter');
    Route::delete('/{rapport}/presences', [RapportReunionController::class, 'supprimerPresence'])->name('presences.supprimer');

    // ================================
    // GESTION DES ACTIONS DE SUIVI
    // ================================

    Route::post('/{rapport}/actions', [RapportReunionController::class, 'ajouterAction'])->name('actions.ajouter');
    Route::post('/{rapport}/actions/terminer', [RapportReunionController::class, 'terminerAction'])->name('actions.terminer');



    // Export PDF d'un rapport individuel
Route::get('/{rapport}/pdf', [RapportReunionController::class, 'downloadPDF'])->name('pdf');

// Export PDF individuel via une route dédiée
Route::get('/{rapport}/export-pdf', [RapportReunionController::class, 'exportRapportPDF'])->name('export-pdf');





    // ================================
    // STATISTIQUES ET EXPORT
    // ================================

    Route::get('/admin/statistiques', [RapportReunionController::class, 'statistiques'])->name('statistiques');

    // Route avec cache pour les statistiques
    Route::get('/admin/statistiques/cached', [RapportReunionController::class, 'statistiques'])
        ->middleware('cache.headers:public;max_age=3600')->name('statistiques.cached');


    // ================================
    // PAGES SPÉCIALISÉES (utilisant index avec filtres)
    // ================================

    // Rapports en attente (brouillon + en_revision)
    Route::get('/workflow/en-attente', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['statut' => ['brouillon', 'en_revision']])
        );
    })->name('en-attente');

    // Rapports publiés
    Route::get('/workflow/publies', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['statut' => 'publie'])
        );
    })->name('publies');

    // Mes rapports (rédacteur connecté)
    Route::get('/workflow/mes-rapports', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['redacteur_id' => auth()->id()])
        );
    })->name('mes-rapports');

    // Rapports à valider (pour les validateurs)
    Route::get('/workflow/a-valider', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['statut' => 'en_revision'])
        );
    })->name('a-valider');

    // Archives (soft deleted)
    Route::get('/workflow/archives', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['include_deleted' => true, 'deleted_only' => true])
        );
    })->name('archives')->middleware('can:viewDeleted,App\Models\RapportReunion');

    // ================================
    // ROUTES DE RACCOURCIS RAPIDES
    // ================================

    // Rapports récents (30 derniers jours)
    Route::get('/raccourcis/recents', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['date_debut' => now()->subDays(30)->format('Y-m-d')])
        );
    })->name('recents');

    // Rapports avec satisfaction élevée
    Route::get('/raccourcis/satisfaction-elevee', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['note_min' => 4])
        );
    })->name('satisfaction-elevee');

    // Rapports avec actions en cours
    Route::get('/raccourcis/avec-actions', function () {
        return app(RapportReunionController::class)->index(
            request()->merge(['avec_actions' => true])
        );
    })->name('avec-actions');

    // ================================
    // RESTAURATION (soft deletes)
    // ================================

    Route::post('/{id}/restore', function ($id) {
        $rapport = \App\Models\RapportReunion::withTrashed()->findOrFail($id);
        $rapport->restore();

        return redirect()->route('private.rapports-reunions.show', $rapport)
                       ->with('success', 'Rapport restauré avec succès');
    })->name('restore')->middleware('can:restore,App\Models\RapportReunion');

});

// ================================
// ROUTES API (optionnelles)
// ================================



// ================================
// REDIRECTIONS POUR COMPATIBILITÉ
// ================================

// Anciennes routes vers nouvelles routes
Route::redirect('/rapports-reunions', '/private/rapports-reunions', 301);
Route::redirect('/admin/rapports-reunions', '/private/rapports-reunions', 301);
Route::redirect('/gestion/rapports-reunions', '/private/rapports-reunions', 301);
Route::redirect('/rapports', '/private/rapports-reunions', 301);

// Redirections spécifiques
Route::redirect('/rapports-reunions/statistiques', '/private/rapports-reunions/admin/statistiques', 301);
Route::redirect('/rapports-reunions/export', '/private/rapports-reunions/admin/export', 301);

// ================================
// ROUTES DE FALLBACK (optionnelles)
// ================================

// Route de fallback pour UUIDs malformés
Route::fallback(function () {
    if (request()->is('private/rapports-reunions/*')) {
        return redirect()->route('private.rapports-reunions.index')
                       ->withErrors('Rapport non trouvé ou lien invalide');
    }
});

/*
|--------------------------------------------------------------------------
| Configuration des contraintes de routes
|--------------------------------------------------------------------------
*/

// Pattern global pour les UUIDs dans toutes les routes de rapports
Route::pattern('rapport', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

// Middleware groups personnalisés
Route::middlewareGroup('rapport.owner', [
    'auth',
    'verified',
    'user.status',
    'can:update,rapport'
]);

Route::middlewareGroup('rapport.validator', [
    'auth',
    'verified',
    'user.status',
    'can:validate,rapport'
]);

Route::middlewareGroup('rapport.admin', [
    'auth',
    'verified',
    'user.status',
    'role:admin|super-admin'
]);

