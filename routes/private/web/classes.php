<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ClasseController;
use Illuminate\Support\Facades\Request;



// ========== ROUTES WEB PROTÉGÉES ==========
Route::middleware(['auth', 'user.status'])->prefix('dashboard/classes')->name('private.classes.')->group(function () {

    // Liste des classes
    Route::get('', [ClasseController::class, 'index'])->middleware('permission:classes.read')->name('index');

    // Afficher le formulaire de création
    Route::get('/create', [ClasseController::class, 'create'])->middleware('permission:classes.create')->name('create');

    // Enregistrer une nouvelle classe
    Route::post('', [ClasseController::class, 'store'])->middleware('permission:classes.create')->name('store');

        // Statistiques globales des classes
    Route::get('/statistiques', [ClasseController::class, 'statistiques'])->middleware('permission:classes.statistics')->name('statistiques');

    // Afficher une classe spécifique
    Route::get('/{classe}', [ClasseController::class, 'show'])->middleware('permission:classes.read')->name('show');
    Route::get('/{classe}/get-membres-disponibles', [ClasseController::class, 'getUtilisateursDisponibles'])->middleware('permission:classes.read')->name('getUtilisateursDisponibles');
    Route::get('/{classe}/get-membres-desinscrir', [ClasseController::class, 'desinscrireUtilisateur'])->middleware('permission:classes.read')->name('desinscrireUtilisateur');
    Route::post('/{classe}/retirer-responsable', [ClasseController::class, 'retirerResponsable'])->middleware('permission:classes.update')->name('retirerResponsable');

    // Afficher le formulaire d'édition
    Route::get('/{classe}/edit', [ClasseController::class, 'edit'])->middleware('permission:classes.update')->name('edit');

    // Mettre à jour une classe (PUT et PATCH pour compatibilité)
    Route::put('/{classe}', [ClasseController::class, 'update'])->middleware('permission:classes.update')->name('update');

    Route::patch('/{classe}', [ClasseController::class, 'update'])->middleware('permission:classes.update')->name('patch');

    // Supprimer une classe
    Route::delete('/{classe}', [ClasseController::class, 'destroy'])->middleware('permission:classes.delete')->name('destroy');


    // Dupliquer une classe
    Route::post('/{classe}/duplicate', [ClasseController::class, 'duplicate'])->middleware('permission:classes.create')->name('duplicate');

    // Exporter les données d'une classe
    Route::get('/{classe}/export', [ClasseController::class, 'export'])->middleware('permission:classes.export')->name('export');

    // Actions en lot
    Route::post('/bulk-actions', [ClasseController::class, 'bulkActions'])->middleware('permission:classes.bulk-actions')->name('bulk-actions');

    // Archiver une classe
    Route::patch('/{classe}/archive', [ClasseController::class, 'archive'])->middleware('permission:classes.archive')->name('archive');

    // Restaurer une classe archivée
    Route::patch('/{classe}/restore', [ClasseController::class, 'restore'])->middleware('permission:classes.restore')->name('restore');

    // ========== ROUTES DES STATISTIQUES ==========

    // Récupérer tous les membres d'une classe
    Route::get('/{classe}/membres', [ClasseController::class, 'getMembers'])->middleware('permission:classes.read')->name('membres');

    // Ajouter plusieurs nouveaux membres à une classe
    Route::post('/{classe}/ajouter-membres', [ClasseController::class, 'ajouterNouveauxMembres'])->middleware('permission:classes.manage-members')->name('ajouter-membres');


    Route::get('/{classe}/membres-responsables', [ClasseController::class, 'getMembresForResponsables'])->name('getMembresForResponsables');

    Route::post('/{classe}/update-responsabilite', [ClasseController::class, 'updateResponsabilite'])->name('updateResponsabilite');


    // ========== ROUTES API POUR LA GESTION DES MEMBRES ==========


    // API: Récupérer tous les membres d'une classe
    Route::get('/{classe}/members', [ClasseController::class, 'getMembers'])->middleware('permission:classes.read')->name('members');

    // API: Ajouter plusieurs nouveaux membres à une classe
    Route::post('/{classe}/members/bulk-add', [ClasseController::class, 'ajouterNouveauxMembres'])->middleware('permission:classes.manage-members')->name('bulk-add-members');

});

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
