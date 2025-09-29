<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\EventController;
use App\Http\Controllers\Private\Web\InscriptionEventController;

/*
|--------------------------------------------------------------------------
| Routes des Événements
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des événements
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('events')->name('private.events.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Liste et recherche des événements
    Route::get('/', [EventController::class, 'index'])->middleware('permission:events.read')->name('index');

    // Formulaire de création
    Route::get('/create', [EventController::class, 'create'])->middleware('permission:events.create')->name('create');

    // Enregistrement d'un nouvel événement
    Route::post('/', [EventController::class, 'store'])->middleware('permission:events.create')->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [EventController::class, 'statistiques'])->middleware('cache.headers:public;max_age=3600')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/planning', [EventController::class, 'planning'])->middleware('permission:events.planning')->name('planning');

    Route::get('/statistiques', [EventController::class, 'statistiques'])->middleware('permission:events.statistics')->name('statistiques');

    Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware('permission:events.dashboard')->name('dashboard');

    // Affichage d'un événement spécifique
    Route::get('/{event}', [EventController::class, 'show'])->middleware('permission:events.read')->name('show');

    // Formulaire d'édition
    Route::get('/{event}/edit', [EventController::class, 'edit'])->middleware('permission:events.update')->name('edit');

    // Mise à jour d'un événement
    Route::put('/{event}', [EventController::class, 'update'])->middleware('permission:events.update')->name('update');

    // Suppression d'un événement
    Route::delete('/{event}', [EventController::class, 'destroy'])->middleware('permission:events.delete')->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{event}/strict', [EventController::class, 'show'])->where('event', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->middleware('permission:events.read')->name('show.strict');

    // Gestion du statut des événements
    Route::post('/{event}/statut', [EventController::class, 'changerStatut'])->middleware('permission:events.change-status')->name('statut');
    Route::post('/{event}/publier', [EventController::class, 'publier'])->middleware('permission:events.update')->name('publier');

    // Duplication d'événement
    Route::post('/{event}/dupliquer', [EventController::class, 'dupliquer'])->middleware('permission:events.duplicate')->name('dupliquer');

    // Restauration d'un événement supprimé
    Route::patch('/{id}/restore', [EventController::class, 'restore'])->middleware('permission:events.restore')->name('restore')->withTrashed();

    // Routes spécifiques pour les inscriptions à un événement
    Route::prefix('/{event}')->middleware('permission:events.manage-inscriptions')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        // Gestion des inscriptions
        Route::get('/inscriptions', [InscriptionEventController::class, 'inscriptionsEvent'])->middleware('permission:events.read')->name('inscriptions');

        // Gestion de la liste d'attente
        Route::get('/liste-attente', [InscriptionEventController::class, 'listeAttente'])->middleware('permission:events.read')->name('liste-attente');

        // Export des participants
        Route::get('/export/participants', [EventController::class, 'exportParticipants'])->middleware('permission:events.read')->name('export.participants');

        // Envoi d'emails aux participants
        Route::post('/notifications/envoyer', [EventController::class, 'envoyerNotification'])->middleware('permission:events.update')->name('notifications.envoyer');

        Route::post('/inscriptions/ajouter', [InscriptionEventController::class, 'ajouterInscription'])->middleware('permission:events.update')->name('inscriptions.ajouter');

        Route::put('/inscriptions/{inscription}', [InscriptionEventController::class, 'modifierInscription'])->middleware('permission:events.update')->name('inscriptions.modifier');

        Route::put('/inscriptions/{inscription}', [InscriptionEventController::class, 'annulerInscription'])->middleware('permission:events.update')->name('inscriptions.annuler');

        Route::put('/inscriptions/{inscription}/reactiver', [InscriptionEventController::class, 'reactivateInscription'])->middleware('permission:events.update')->name('inscriptions.reactivate');

        Route::delete('/inscriptions/{inscription}', [InscriptionEventController::class, 'supprimerInscription'])->middleware('permission:events.update')->name('inscriptions.supprimer');

        Route::post('/liste-attente/promouvoir/{inscription}', [InscriptionEventController::class, 'promouvoirInscription'])->middleware('permission:events.update')->name('liste-attente.promouvoir');
    });

    // Routes pour les événements récurrents
    Route::prefix('/{event}/recurrence')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::post('/activer', [EventController::class, 'activerRecurrence'])->middleware('permission:events.update')->name('recurrence.activer');

        Route::post('/desactiver', [EventController::class, 'desactiverRecurrence'])->middleware('permission:events.update')->name('recurrence.desactiver');

        Route::post('/generer-occurrences', [EventController::class, 'genererOccurrences'])->middleware('permission:events.update')->name('recurrence.generer');
    });

    // Routes pour la gestion des médias
    Route::prefix('/{event}/medias')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::post('/images', [EventController::class, 'ajouterImages'])->middleware('permission:events.update')->name('medias.images.ajouter');

        Route::delete('/images/{image}', [EventController::class, 'supprimerImage'])->middleware('permission:events.update')->name('medias.images.supprimer');

        Route::post('/documents', [EventController::class, 'ajouterDocuments'])->middleware('permission:events.update')->name('medias.documents.ajouter');

        Route::delete('/documents/{document}', [EventController::class, 'supprimerDocument'])->middleware('permission:events.update')->name('medias.documents.supprimer');
    });

    // Routes pour la gestion financière
    Route::prefix('/{event}/finances')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::get('/', [EventController::class, 'finances'])->middleware('permission:events.update')->name('finances');

        Route::post('/budget', [EventController::class, 'mettreAJourBudget'])->middleware('permission:events.update')->name('finances.budget');

        Route::post('/recettes', [EventController::class, 'enregistrerRecettes'])->middleware('permission:events.update')->name('finances.recettes');

        Route::post('/depenses', [EventController::class, 'enregistrerDepenses'])->middleware('permission:events.update')->name('finances.depenses');

        Route::get('/rapport', [EventController::class, 'rapportFinancier'])->middleware('permission:events.update')->name('finances.rapport');
    });
});



