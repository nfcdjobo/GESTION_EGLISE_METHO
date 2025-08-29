<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\CulteController;
use App\Http\Controllers\Private\Web\ParticipantCulteController;

/*
|--------------------------------------------------------------------------
| Routes des Cultes
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des cultes religieux
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('dashboard/cultes')->name('private.cultes.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    Route::get('/', [CulteController::class, 'index'])->name('index');

    Route::get('/create', [CulteController::class, 'create'])->name('create');



    Route::post('/', [CulteController::class, 'store'])->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [CulteController::class, 'statistiques'])->middleware('cache.headers:public;max_age=3600')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/planning', [CulteController::class, 'planning'])->name('planning');

    Route::get('/statistiques', [CulteController::class, 'statistiques'])->name('statistiques');

    Route::get('/dashboard', [CulteController::class, 'dashboard'])->name('dashboard');

    Route::get('/{culte}', [CulteController::class, 'show'])->name('show');

    Route::get('/{culte}/edit', [CulteController::class, 'edit'])->name('edit');


    Route::put('/{culte}', [CulteController::class, 'update'])->name('update');

    Route::delete('/{culte}', [CulteController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{culte}/strict', [CulteController::class, 'show'])->where('culte', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('show.strict');

    // Gestion du statut des cultes
    Route::post('/{culte}/statut', [CulteController::class, 'changerStatut'])->name('statut');

    // Duplication de culte
    Route::post('/{culte}/dupliquer', [CulteController::class, 'dupliquer'])->name('dupliquer');

    // Restauration d'un culte supprimé
    Route::patch('/{id}/restore', [CulteController::class, 'restore'])->name('restore')->withTrashed();


    // Routes spécifiques pour les participants d'un culte
    Route::get('/{culte}/participants', [ParticipantCulteController::class, 'participantsCulte'])
        ->name('participants')
        ->where('culte', '[0-9a-f-]{36}');

    Route::post('/{culte}/participants/ajouter', [ParticipantCulteController::class, 'ajouterParticipant'])
        ->name('participants.ajouter')
        ->where('culte', '[0-9a-f-]{36}');



});




// Redirection des anciennes URLs
Route::redirect('/cultes', '/private/cultes', 301);
Route::redirect('/admin/cultes', '/private/cultes', 301);


