<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ClasseController;
use Illuminate\Support\Facades\Request;

/*
|--------------------------------------------------------------------------
| Routes de gestion des classes
|--------------------------------------------------------------------------
|
| Routes protégées pour la gestion des classes avec système de permissions
| Inclut les routes Web et API
|
*/

// ========== ROUTES WEB PROTÉGÉES ==========
Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')->group(function () {

    // ========== ROUTES CRUD PRINCIPALES ==========

    // Liste des classes
    Route::get('/classes', [ClasseController::class, 'index'])
        ->middleware('permission:classes.read')
        ->name('classes.index');

    // Afficher le formulaire de création
    Route::get('/classes/create', [ClasseController::class, 'create'])
        ->middleware('permission:classes.create')
        ->name('classes.create');

    // Enregistrer une nouvelle classe
    Route::post('/classes', [ClasseController::class, 'store'])
        ->middleware('permission:classes.create')
        ->name('classes.store');

        // Statistiques globales des classes
    Route::get('/classes/statistiques', [ClasseController::class, 'statistiques'])
        // ->middleware('permission:classes.statistics')
        ->name('classes.statistiques');

    // Afficher une classe spécifique
    Route::get('/classes/{classe}', [ClasseController::class, 'show'])
        ->middleware('permission:classes.read')
        ->name('classes.show');

    // Afficher le formulaire d'édition
    Route::get('/classes/{classe}/edit', [ClasseController::class, 'edit'])
        ->middleware('permission:classes.update')
        ->name('classes.edit');

    // Mettre à jour une classe (PUT et PATCH pour compatibilité)
    Route::put('/classes/{classe}', [ClasseController::class, 'update'])
        ->middleware('permission:classes.update')
        ->name('classes.update');

    Route::patch('/classes/{classe}', [ClasseController::class, 'update'])
        ->middleware('permission:classes.update')
        ->name('classes.patch');

    // Supprimer une classe
    Route::delete('/classes/{classe}', [ClasseController::class, 'destroy'])
        ->middleware('permission:classes.delete')
        ->name('classes.destroy');

    // ========== ROUTES D'ACTIONS SPÉCIALES ==========

    // Inscrire un utilisateur à une classe
    Route::post('/classes/{classe}/inscrire', [ClasseController::class, 'inscrireUtilisateur'])
        ->middleware('permission:classes.manage-members')
        ->name('classes.inscrire');

    // Désinscrire un utilisateur d'une classe
    Route::post('/classes/{classe}/desinscrire', [ClasseController::class, 'desinscrireUtilisateur'])
        ->middleware('permission:classes.manage-members')
        ->name('classes.desinscrire');

    // Supprimer un utilisateur d'une classe (alias pour compatibilité)
    Route::delete('/classes/{classe}/membres/{user}', [ClasseController::class, 'desinscrireUtilisateur'])
        ->middleware('permission:classes.manage-members')
        ->name('classes.remove-member');



        // Activer/Désactiver une classe
Route::patch('/classes/{classe}/toggle-status', [ClasseController::class, 'toggleStatus'])
    ->middleware('permission:classes.update')
    ->name('classes.toggle-status');

// Dupliquer une classe
Route::post('/classes/{classe}/duplicate', [ClasseController::class, 'duplicate'])
    ->middleware('permission:classes.create')
    ->name('classes.duplicate');

// Exporter les données d'une classe
Route::get('/classes/{classe}/export', [ClasseController::class, 'export'])
    ->middleware('permission:classes.export')
    ->name('classes.export');

// Actions en lot
Route::post('/classes/bulk-actions', [ClasseController::class, 'bulkActions'])
    ->middleware('permission:classes.bulk-actions')
    ->name('classes.bulk-actions');

// Archiver une classe
Route::patch('/classes/{classe}/archive', [ClasseController::class, 'archive'])
    ->middleware('permission:classes.archive')
    ->name('classes.archive');

// Restaurer une classe archivée
Route::patch('/classes/{classe}/restore', [ClasseController::class, 'restore'])
    ->middleware('permission:classes.restore')
    ->name('classes.restore');

    // ========== ROUTES DES STATISTIQUES ==========



    // Statistiques d'une classe spécifique
    Route::get('/classes/{classe}/statistiques', [ClasseController::class, 'show'])
        ->middleware('permission:classes.read')
        ->name('classes.show-stats');




        // ========== ROUTES POUR LA GESTION DES MEMBRES ==========

// Dans le groupe Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')

// Récupérer tous les membres d'une classe
Route::get('/classes/{classe}/membres', [ClasseController::class, 'getMembers'])
    ->middleware('permission:classes.read')
    ->name('classes.membres');

// Ajouter plusieurs nouveaux membres à une classe
Route::post('/classes/{classe}/ajouter-membres', [ClasseController::class, 'ajouterNouveauxMembres'])
    ->middleware('permission:classes.manage-members')
    ->name('classes.ajouter-membres');

// Récupérer les utilisateurs disponibles (sans classe) pour une classe
Route::get('/classes/{classe}/utilisateurs-disponibles', [ClasseController::class, 'getUtilisateursDisponibles'])
    ->middleware('permission:classes.read')
    ->name('classes.utilisateurs-disponibles');

// ========== ROUTES API POUR LA GESTION DES MEMBRES ==========

// Dans le groupe Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')

// API: Récupérer tous les membres d'une classe
Route::get('/classes/{classe}/members', [ClasseController::class, 'getMembers'])
    ->middleware('permission:classes.read')
    ->name('classes.members');

// API: Ajouter plusieurs nouveaux membres à une classe
Route::post('/classes/{classe}/members/bulk-add', [ClasseController::class, 'ajouterNouveauxMembres'])
    ->middleware('permission:classes.manage-members')
    ->name('classes.bulk-add-members');

// API: Récupérer les utilisateurs disponibles pour une classe
Route::get('/classes/{classe}/available-users', [ClasseController::class, 'getUtilisateursDisponibles'])
    ->middleware('permission:classes.read')
    ->name('classes.available-users');
});

// ========== ROUTES API ==========
Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.v1.')->group(function () {

    // Routes API pour les classes (même contrôleur, détection automatique via isApiRequest)
    Route::apiResource('classes', ClasseController::class)->parameters([
        'classes' => 'classe'
    ])->middleware('permission:classes.read');

    // Actions spéciales API
    Route::post('/classes/{classe}/members', [ClasseController::class, 'inscrireUtilisateur'])
        ->middleware('permission:classes.manage-members')
        ->name('classes.add-member');

    Route::delete('/classes/{classe}/members/{user}', [ClasseController::class, 'desinscrireUtilisateur'])
        ->middleware('permission:classes.manage-members')
        ->name('classes.remove-member');

    // Statistiques API
    Route::get('/classes/statistics/global', [ClasseController::class, 'statistiques'])
        ->middleware('permission:classes.statistics')
        ->name('classes.global-stats');

    Route::get('/classes/{classe}/statistics', [ClasseController::class, 'show'])
        ->middleware('permission:classes.read')
        ->name('classes.stats');
});

// ========== ROUTES PUBLIQUES (si nécessaire) ==========
Route::prefix('public')->name('public.')->group(function () {

    // Liste publique des classes (sans informations sensibles)
    Route::get('/classes', [ClasseController::class, 'index'])
        ->name('classes.public');

    // Détails publics d'une classe
    Route::get('/classes/{classe}', [ClasseController::class, 'show'])
        ->name('classes.show-public');
});

// ========== ROUTES AJAX/JAVASCRIPT ==========
Route::middleware(['auth', 'user.status'])->prefix('ajax')->name('ajax.')->group(function () {

    // Recherche en temps réel
    Route::get('/classes/search', [ClasseController::class, 'index'])
        ->middleware('permission:classes.read')
        ->name('classes.search');

    // Validation en temps réel
    Route::post('/classes/validate', function(Request $request) {
        // Validation côté serveur pour les formulaires
        return response()->json(['valid' => true]);
    })->name('classes.validate');
});

// ========== FALLBACK ET REDIRECTIONS ==========

// Redirection pour l'ancien système (si migration depuis un autre système)
Route::redirect('/groups', '/private/classes')->name('groups.redirect');
Route::redirect('/admin/classes', '/private/classes')->name('admin.classes.redirect');
Route::redirect('/education/classes', '/private/classes')->name('education.classes.redirect');

// ========== DÉFINITION DES CONTRAINTES DE ROUTES ==========

// Contraintes pour s'assurer que les paramètres sont corrects
Route::pattern('classe', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('user', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('session', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('template', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID

/*
|--------------------------------------------------------------------------
| Routes supplémentaires potentielles
|--------------------------------------------------------------------------
|
| Méthodes additionnelles que vous pourriez vouloir ajouter au contrôleur
|
*/


// Si vous ajoutez ces méthodes au contrôleur, décommentez les routes correspondantes :



