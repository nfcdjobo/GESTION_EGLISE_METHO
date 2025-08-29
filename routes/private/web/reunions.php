<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ReunionController;


/*
|--------------------------------------------------------------------------
| Routes des Réunions
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des réunions spécifiques
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('dashboard/reunions')->name('private.reunions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD principales
    Route::get('/', [ReunionController::class, 'index'])->name('index');
    Route::get('/create', [ReunionController::class, 'create'])->name('create');
    Route::post('/', [ReunionController::class, 'store'])->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [ReunionController::class, 'statistiques'])
        ->middleware('cache.headers:public;max_age=1800')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/statistiques', [ReunionController::class, 'statistiques'])->name('statistiques');
    Route::get('/a-venir', [ReunionController::class, 'aVenir'])->name('a-venir');
    Route::get('/du-jour', [ReunionController::class, 'duJour'])->name('du-jour');
    Route::get('/calendrier', [ReunionController::class, 'calendrier'])->name('calendrier');
    Route::get('/publiques', [ReunionController::class, 'reunionsPubliques'])->name('publiques');
    Route::get('/diffusion-live', [ReunionController::class, 'avecDiffusionLive'])->name('diffusion-live');
    Route::get('/options', [ReunionController::class, 'options'])->name('options');

    // Routes avec paramètres de réunion
    Route::get('/{reunion}', [ReunionController::class, 'show'])->name('show');
    Route::get('/{reunion}/edit', [ReunionController::class, 'edit'])->name('edit');
    Route::put('/{reunion}', [ReunionController::class, 'update'])->name('update');
    Route::delete('/{reunion}', [ReunionController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{reunion}/strict', [ReunionController::class, 'show'])
        ->where('reunion', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
        ->name('show.strict');

    // Gestion du cycle de vie des réunions
    Route::post('/{reunion}/confirmer', [ReunionController::class, 'confirmer'])->name('confirmer');
    Route::post('/{reunion}/commencer', [ReunionController::class, 'commencer'])->name('commencer');
    Route::post('/{reunion}/terminer', [ReunionController::class, 'terminer'])->name('terminer');
    Route::post('/{reunion}/annuler', [ReunionController::class, 'annuler'])->name('annuler');
    Route::post('/{reunion}/reporter', [ReunionController::class, 'reporter'])->name('reporter');
    Route::post('/{reunion}/suspendre', [ReunionController::class, 'suspendre'])->name('suspendre');
    Route::post('/{reunion}/reprendre', [ReunionController::class, 'reprendre'])->name('reprendre');

    // Gestion des participants et présences
    Route::post('/{reunion}/presences', [ReunionController::class, 'marquerPresences'])->name('marquer-presences');
    Route::post('/{reunion}/inscrire-participant', [ReunionController::class, 'inscrireParticipant'])->name('inscrire-participant');


    // Gestion spirituelle
    Route::post('/{reunion}/resultats-spirituel', [ReunionController::class, 'ajouterResultatsSpirituel'])->name('resultats-spirituel');
    Route::post('/{reunion}/temoignages', [ReunionController::class, 'ajouterTemoignage'])->name('temoignages');
    Route::post('/{reunion}/demandes-priere', [ReunionController::class, 'ajouterDemandesPriere'])->name('demandes-priere');

    // Évaluation et feedback
    Route::post('/{reunion}/evaluer', [ReunionController::class, 'evaluer'])->name('evaluer');
    Route::get('/{reunion}/evaluation', [ReunionController::class, 'afficherEvaluation'])->name('evaluation');

    // Duplication et récurrence
    Route::post('/{reunion}/dupliquer', [ReunionController::class, 'dupliquer'])->name('dupliquer');
    Route::post('/{reunion}/creer-recurrence', [ReunionController::class, 'creerRecurrence'])->name('creer-recurrence');

    // Notifications et rappels
    Route::post('/{reunion}/envoyer-rappel', [ReunionController::class, 'envoyerRappel'])->name('envoyer-rappel');
    Route::post('/{reunion}/notifier-participants', [ReunionController::class, 'notifierParticipants'])->name('notifier-participants');

    // Upload de médias
    Route::post('/{reunion}/upload-photos', [ReunionController::class, 'uploadPhotos'])->name('upload.photos');
    Route::post('/{reunion}/upload-documents', [ReunionController::class, 'uploadDocuments'])->name('upload.documents');

    // Restauration d'une réunion supprimée
    Route::post('/{id}/restore', [ReunionController::class, 'restore'])->name('restore')->withTrashed();

    Route::delete('/{reunion}/desinscrire-participant/{participant}', [ReunionController::class, 'desinscrireParticipant'])->name('desinscrire-participant');

});


// Redirections réunions
Route::redirect('/reunions', '/private/reunions', 301);
Route::redirect('/admin/reunions', '/private/reunions', 301);
Route::redirect('/gestion/reunions', '/private/reunions', 301);
Route::redirect('/evenements', '/private/reunions', 301);
