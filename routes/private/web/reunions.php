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

Route::prefix('reunions')->name('private.reunions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD principales
    Route::get('/', [ReunionController::class, 'index'])->middleware('permission:reunions.read')->name('index');
    Route::get('/create', [ReunionController::class, 'create'])->middleware('permission:reunions.create')->name('create');
    Route::post('/', [ReunionController::class, 'store'])->middleware('permission:reunions.create')->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [ReunionController::class, 'statistiques'])->middleware('cache.headers:public;max_age=1800')->middleware('permission:reunions.statistics')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/statistiques', [ReunionController::class, 'statistiques'])->middleware('permission:reunions.statistics')->name('statistiques');
    Route::get('/a-venir', [ReunionController::class, 'aVenir'])->middleware('permission:reunions.upcoming')->name('a-venir');
    Route::get('/du-jour', [ReunionController::class, 'duJour'])->middleware('permission:reunions.today')->name('du-jour');
    Route::get('/calendrier', [ReunionController::class, 'calendrier'])->middleware('permission:reunions.calendar')->name('calendrier');
    Route::get('/publiques', [ReunionController::class, 'reunionsPubliques'])->middleware('permission:reunions.public')->name('publiques');
    Route::get('/diffusion-live', [ReunionController::class, 'avecDiffusionLive'])->middleware('permission:reunions.live-stream')->name('diffusion-live');
    Route::get('/options', [ReunionController::class, 'options'])->middleware('permission:reunions.read')->name('options');

    // Routes avec paramètres de réunion
    Route::get('/{reunion}', [ReunionController::class, 'show'])->middleware('permission:reunions.read')->name('show');
    Route::get('/{reunion}/edit', [ReunionController::class, 'edit'])->middleware('permission:reunions.update')->name('edit');
    // Route avec validation UUID stricte
    Route::get('/{reunion}/strict', [ReunionController::class, 'show'])->where('reunion', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->middleware('permission:reunions.read')->name('show.strict');
    Route::put('/{reunion}', [ReunionController::class, 'update'])->middleware('permission:reunions.update')->name('update');
    Route::delete('/{reunion}', [ReunionController::class, 'destroy'])->middleware('permission:reunions.delete')->name('destroy');



    // Gestion du cycle de vie des réunions
    Route::post('/{reunion}/confirmer', [ReunionController::class, 'confirmer'])->middleware('permission:reunions.confirm')->name('confirmer');
    Route::post('/{reunion}/commencer', [ReunionController::class, 'commencer'])->middleware('permission:reunions.start')->name('commencer');
    Route::post('/{reunion}/terminer', [ReunionController::class, 'terminer'])->middleware('permission:reunions.end')->name('terminer');
    Route::post('/{reunion}/annuler', [ReunionController::class, 'annuler'])->middleware('permission:reunions.cancel')->name('annuler');
    Route::post('/{reunion}/reporter', [ReunionController::class, 'reporter'])->middleware('permission:reunions.postpone')->name('reporter');
    Route::post('/{reunion}/suspendre', [ReunionController::class, 'suspendre'])->middleware('permission:reunions.suspend')->name('suspendre');
    Route::post('/{reunion}/reprendre', [ReunionController::class, 'reprendre'])->middleware('permission:reunions.resume')->name('reprendre');

    // Gestion des participants et présences
    Route::post('/{reunion}/presences', [ReunionController::class, 'marquerPresences'])->middleware('permission:reunions.mark-attendance')->name('marquer-presences');
    Route::post('/{reunion}/inscrire-participant', [ReunionController::class, 'inscrireParticipant'])->middleware('permission:reunions.register-participant')->name('inscrire-participant');


    // Gestion spirituelle
    Route::post('/{reunion}/resultats-spirituel', [ReunionController::class, 'ajouterResultatsSpirituel'])->middleware('permission:reunions.add-spiritual-results')->name('resultats-spirituel');
    Route::post('/{reunion}/temoignages', [ReunionController::class, 'ajouterTemoignage'])->middleware('permission:reunions.add-testimonies')->name('temoignages');
    Route::post('/{reunion}/demandes-priere', [ReunionController::class, 'ajouterDemandesPriere'])->middleware('permission:reunions.add-prayer-requests')->name('demandes-priere');

    // Évaluation et feedback
    Route::post('/{reunion}/evaluation', [ReunionController::class, 'evaluer'])->middleware('permission:reunions.evaluate')->name('evaluation');
    Route::post('/{reunion}/evaluer', [ReunionController::class, 'afficherEvaluation'])->middleware('permission:reunions.evaluate')->name('evaluer');

    // Duplication et récurrence
    Route::post('/{reunion}/dupliquer', [ReunionController::class, 'dupliquer'])->middleware('permission:reunions.duplicate')->name('dupliquer');
    Route::post('/{reunion}/creer-recurrence', [ReunionController::class, 'creerRecurrence'])->middleware('permission:reunions.create-recurrence')->name('creer-recurrence');

    // Notifications et rappels
    Route::post('/{reunion}/envoyer-rappel', [ReunionController::class, 'envoyerRappel'])->middleware('permission:reunions.send-reminders')->name('envoyer-rappel');
    Route::post('/{reunion}/notifier-participants', [ReunionController::class, 'notifierParticipants'])->middleware('permission:reunions.notify-participants')->name('notifier-participants');

    // Upload de médias
    Route::post('/{reunion}/upload-photos', [ReunionController::class, 'uploadPhotos'])->middleware('permission:reunions.upload-documents')->name('upload.photos');
    Route::post('/{reunion}/upload-documents', [ReunionController::class, 'uploadDocuments'])->middleware('permission:reunions.upload-documents')->name('upload.documents');

    // Restauration d'une réunion supprimée
    Route::post('/{id}/restore', [ReunionController::class, 'restore'])->middleware('permission:reunions.restore')->name('restore')->withTrashed();

    Route::delete('/{reunion}/desinscrire-participant/{participant}', [ReunionController::class, 'desinscrireParticipant'])->middleware('permission:reunions.unregister-participant')->name('desinscrire-participant');

});

