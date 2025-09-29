<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ContactController;

// Routes pour la gestion des contacts d'église

Route::prefix('contacts')->name('private.contacts.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // ========== ROUTES CRUD PRINCIPALES ==========

    Route::get('', [ContactController::class, 'index'])->middleware('permission:contacts.read')->name('index');

    Route::get('/create', [ContactController::class, 'create'])->middleware('permission:contacts.create')->name('create');

    Route::post('contacts', [ContactController::class, 'store'])->middleware('permission:contacts.create')->name('store');

    Route::get('/{contact}', [ContactController::class, 'show'])->middleware('permission:contacts.read')->name('show');

    Route::get('/{contact}/edit', [ContactController::class, 'edit'])->middleware('permission:contacts.update')->name('edit');

    Route::put('/{contact}', [ContactController::class, 'update'])->middleware('permission:contacts.update')->name('update');

    Route::delete('/{contact}', [ContactController::class, 'destroy'])->middleware('permission:contacts.delete')->name('destroy');

    // ========== ROUTES D'ACTIONS SPÉCIALISÉES ==========

    // Vérifier un contact individuel
    Route::patch('/{contact}/verify', [ContactController::class, 'verify'])->middleware('permission:contacts.verify')->name('verify');

    // Actions en masse sur les contacts
    Route::post('/bulk-actions', [ContactController::class, 'bulkActions'])->middleware('permission:contacts.bulk-actions')->name('bulk-actions');

    // ========== RECHERCHE ET GÉOLOCALISATION ==========

    // Recherche géographique par proximité
    Route::get('/search/nearby', [ContactController::class, 'searchNearby'])->middleware('permission:contacts.search-nearby')->name('search.nearby');

    // Recherche avancée de contacts
    Route::get('/search', [ContactController::class, 'search'])->middleware('permission:contacts.advanced-search')->name('search');

    // ========== STATISTIQUES ET RAPPORTS ==========

    // Tableau de bord des statistiques
    Route::get('/statistics', [ContactController::class, 'statistics'])->middleware('permission:contacts.statistics')->name('statistics');

    // Rapport de complétude des contacts
    Route::get('/completeness-report', [ContactController::class, 'completenessReport'])->middleware('permission:contacts.completeness-report')->name('completeness-report');

    // ========== EXPORT ET IMPORT ==========

    // Export des contacts (CSV, JSON, vCard)
    Route::get('/export', [ContactController::class, 'export'])->middleware('permission:contacts.export')->name('export');

    // Import de contacts depuis fichier
    Route::post('/import', [ContactController::class, 'import'])->middleware('permission:contacts.import')->name('import');

    // Télécharger modèle d'import
    Route::get('/import-template', [ContactController::class, 'downloadImportTemplate'])->middleware('permission:contacts.import-template')->name('import-template');



    // ========== VUES PUBLIQUES (pour affichage sur site web) ==========

    // Liste publique des contacts (pour site web)
    Route::get('/public', [ContactController::class, 'publicIndex'])->middleware('permission:contacts.read')->name('public');

    // Carte interactive des églises
    Route::get('/map', [ContactController::class, 'mapView'])->middleware('permission:contacts.read')->name('map');

    // Annuaire des églises par ville
    Route::get('/directory', [ContactController::class, 'directory'])->middleware('permission:contacts.read')->name('directory');

    // ========== INFORMATIONS SPÉCIALISÉES ==========

    // Contacts d'urgence
    Route::get('/emergency', [ContactController::class, 'emergencyContacts'])->middleware('permission:contacts.read')->name('emergency');

    // Informations de dons
    Route::get('/donations', [ContactController::class, 'donationInfo'])->middleware('permission:contacts.read')->name('donations');

    // Réseaux sociaux des églises
    Route::get('/social-media', [ContactController::class, 'socialMedia'])->middleware('permission:contacts.read')->name('social-media');

    // ========== VALIDATION ET MAINTENANCE ==========

    // Valider les URLs et emails
    Route::post('/validate-links', [ContactController::class, 'validateLinks'])->middleware('permission:contacts.validate-links')->name('validate-links');

    // Nettoyer les données obsolètes
    Route::post('/cleanup', [ContactController::class, 'cleanup'])->middleware('permission:contacts.cleanup')->name('cleanup');

    // Synchroniser avec services externes
    Route::post('/sync-external', [ContactController::class, 'syncExternal'])->middleware('permission:contacts.sync-external')->name('sync-external');

        // ========== FONCTIONNALITÉS SPÉCIALISÉES ==========

    // Mettre à jour la visibilité publique
    Route::patch('/{contact}/visibility', [ContactController::class, 'updateVisibility'])->middleware('permission:contacts.visibility')->name('update-visibility');

    // Générer QR code de contact
    Route::get('/{contact}/qr-code', [ContactController::class, 'generateQRCode'])->middleware('permission:contacts.read')->name('qr-code');

    // Géocoder manuellement une adresse
    Route::post('/{contact}/geocode', [ContactController::class, 'geocodeManual'])->middleware('permission:contacts.geocode')->name('geocode');

    // Dupliquer un contact
    Route::post('/{contact}/duplicate', [ContactController::class, 'duplicate'])->middleware('permission:contacts.duplicate')->name('duplicate');

    // Fusionner des contacts
    Route::post('/merge', [ContactController::class, 'merge'])->middleware('permission:contacts.merge')->name('merge');
});



// ========== DÉFINITION DES CONTRAINTES DE ROUTES ==========

// Contraintes pour s'assurer que les paramètres sont corrects
Route::pattern('contact', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('ville', '[a-zA-Z\-\s]+'); // Nom de ville
Route::pattern('denomination', '[a-zA-Z\-\s]+'); // Dénomination


