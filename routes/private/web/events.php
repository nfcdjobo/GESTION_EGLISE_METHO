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

Route::prefix('dashboard/events')->name('private.events.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Liste et recherche des événements
    Route::get('/', [EventController::class, 'index'])->name('index');

    // Formulaire de création
    Route::get('/create', [EventController::class, 'create'])->name('create');

    // Enregistrement d'un nouvel événement
    Route::post('/', [EventController::class, 'store'])->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [EventController::class, 'statistiques'])
        ->middleware('cache.headers:public;max_age=3600')
        ->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/planning', [EventController::class, 'planning'])->name('planning');

    Route::get('/statistiques', [EventController::class, 'statistiques'])->name('statistiques');

    Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');

    // Affichage d'un événement spécifique
    Route::get('/{event}', [EventController::class, 'show'])->name('show');

    // Formulaire d'édition
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');

    // Mise à jour d'un événement
    Route::put('/{event}', [EventController::class, 'update'])->name('update');

    // Suppression d'un événement
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{event}/strict', [EventController::class, 'show'])
        ->where('event', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
        ->name('show.strict');

    // Gestion du statut des événements
    Route::post('/{event}/statut', [EventController::class, 'changerStatut'])->name('statut');

    // Duplication d'événement
    Route::post('/{event}/dupliquer', [EventController::class, 'dupliquer'])->name('dupliquer');

    // Restauration d'un événement supprimé
    Route::patch('/{id}/restore', [EventController::class, 'restore'])
        ->name('restore')
        ->withTrashed();

    // Routes spécifiques pour les inscriptions à un événement
    Route::prefix('/{event}')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        // Gestion des inscriptions
        Route::get('/inscriptions', [InscriptionEventController::class, 'inscriptionsEvent'])
            ->name('inscriptions');

        Route::post('/inscriptions/ajouter', [InscriptionEventController::class, 'ajouterInscription'])
            ->name('inscriptions.ajouter');

        Route::put('/inscriptions/{inscription}', [InscriptionEventController::class, 'modifierInscription'])
            ->name('inscriptions.modifier');

        Route::put('/inscriptions/{inscription}', [InscriptionEventController::class, 'annulerInscription'])
            ->name('inscriptions.annuler');

        Route::put('/inscriptions/{inscription}/reactiver', [InscriptionEventController::class, 'reactivateInscription'])
            ->name('inscriptions.reactivate');

        Route::delete('/inscriptions/{inscription}', [InscriptionEventController::class, 'supprimerInscription'])
            ->name('inscriptions.supprimer');

        // Gestion de la liste d'attente
        Route::get('/liste-attente', [InscriptionEventController::class, 'listeAttente'])
            ->name('liste-attente');

        Route::post('/liste-attente/promouvoir/{inscription}', [InscriptionEventController::class, 'promouvoirInscription'])
            ->name('liste-attente.promouvoir');

        // Export des participants
        Route::get('/export/participants', [EventController::class, 'exportParticipants'])
            ->name('export.participants');

        // Envoi d'emails aux participants
        Route::post('/notifications/envoyer', [EventController::class, 'envoyerNotification'])
            ->name('notifications.envoyer');
    });

    // Routes pour les événements récurrents
    Route::prefix('/{event}/recurrence')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::post('/activer', [EventController::class, 'activerRecurrence'])
            ->name('recurrence.activer');

        Route::post('/desactiver', [EventController::class, 'desactiverRecurrence'])
            ->name('recurrence.desactiver');

        Route::post('/generer-occurrences', [EventController::class, 'genererOccurrences'])
            ->name('recurrence.generer');
    });

    // Routes pour la gestion des médias
    Route::prefix('/{event}/medias')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::post('/images', [EventController::class, 'ajouterImages'])
            ->name('medias.images.ajouter');

        Route::delete('/images/{image}', [EventController::class, 'supprimerImage'])
            ->name('medias.images.supprimer');

        Route::post('/documents', [EventController::class, 'ajouterDocuments'])
            ->name('medias.documents.ajouter');

        Route::delete('/documents/{document}', [EventController::class, 'supprimerDocument'])
            ->name('medias.documents.supprimer');
    });

    // Routes pour la gestion financière
    Route::prefix('/{event}/finances')->where(['event' => '[0-9a-f-]{36}'])->group(function () {

        Route::get('/', [EventController::class, 'finances'])
            ->name('finances');

        Route::post('/budget', [EventController::class, 'mettreAJourBudget'])
            ->name('finances.budget');

        Route::post('/recettes', [EventController::class, 'enregistrerRecettes'])
            ->name('finances.recettes');

        Route::post('/depenses', [EventController::class, 'enregistrerDepenses'])
            ->name('finances.depenses');

        Route::get('/rapport', [EventController::class, 'rapportFinancier'])
            ->name('finances.rapport');
    });
});




// Redirections des anciennes URLs
Route::redirect('/evenements', '/private/events', 301);
Route::redirect('/admin/evenements', '/private/events', 301);
Route::redirect('/events', '/private/events', 301);
Route::redirect('/admin/events', '/private/events', 301);

// Redirections spécifiques pour maintenir la compatibilité
Route::redirect('/private/evenements', '/private/events', 301);
Route::redirect('/private/evenements/{any}', '/private/events/{any}', 301)->where('any', '.*');
