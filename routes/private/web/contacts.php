<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ContactController;

// Routes pour la gestion des contacts d'église

Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // ========== ROUTES CRUD PRINCIPALES ==========

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // ========== ROUTES D'ACTIONS SPÉCIALISÉES ==========

    // Vérifier un contact individuel
    Route::patch('contacts/{contact}/verify', [ContactController::class, 'verify'])->name('contacts.verify');

    // Actions en masse sur les contacts
    Route::post('contacts/bulk-actions', [ContactController::class, 'bulkActions'])->name('contacts.bulk-actions');

    // ========== RECHERCHE ET GÉOLOCALISATION ==========

    // Recherche géographique par proximité
    Route::get('contacts/search/nearby', [ContactController::class, 'searchNearby'])->name('contacts.search.nearby');

    // Recherche avancée de contacts
    Route::get('contacts-search', [ContactController::class, 'search'])->name('contacts.search');

    // ========== STATISTIQUES ET RAPPORTS ==========

    // Tableau de bord des statistiques
    Route::get('contacts-statistics', [ContactController::class, 'statistics'])->name('contacts.statistics');

    // Rapport de complétude des contacts
    Route::get('contacts-completeness-report', [ContactController::class, 'completenessReport'])->name('contacts.completeness-report');

    // ========== EXPORT ET IMPORT ==========

    // Export des contacts (CSV, JSON, vCard)
    Route::get('contacts-export', [ContactController::class, 'export'])->name('contacts.export');

    // Import de contacts depuis fichier
    Route::post('contacts-import', [ContactController::class, 'import'])->name('contacts.import');

    // Télécharger modèle d'import
    Route::get('contacts-import-template', [ContactController::class, 'downloadImportTemplate'])->name('contacts.import-template');

    // ========== FONCTIONNALITÉS SPÉCIALISÉES ==========

    // Mettre à jour la visibilité publique
    Route::patch('contacts/{contact}/visibility', [ContactController::class, 'updateVisibility'])->name('contacts.update-visibility');

    // Générer QR code de contact
    Route::get('contacts/{contact}/qr-code', [ContactController::class, 'generateQRCode'])->name('contacts.qr-code');

    // Géocoder manuellement une adresse
    Route::post('contacts/{contact}/geocode', [ContactController::class, 'geocodeManual'])->name('contacts.geocode');

    // Dupliquer un contact
    Route::post('contacts/{contact}/duplicate', [ContactController::class, 'duplicate'])->name('contacts.duplicate');

    // Fusionner des contacts
    Route::post('contacts/merge', [ContactController::class, 'merge'])->name('contacts.merge');

    // ========== VUES PUBLIQUES (pour affichage sur site web) ==========

    // Liste publique des contacts (pour site web)
    Route::get('contacts-public', [ContactController::class, 'publicIndex'])->name('contacts.public');

    // Carte interactive des églises
    Route::get('contacts-map', [ContactController::class, 'mapView'])->name('contacts.map');

    // Annuaire des églises par ville
    Route::get('contacts-directory', [ContactController::class, 'directory'])->name('contacts.directory');

    // ========== INFORMATIONS SPÉCIALISÉES ==========

    // Contacts d'urgence
    Route::get('contacts-emergency', [ContactController::class, 'emergencyContacts'])->name('contacts.emergency');

    // Informations de dons
    Route::get('contacts-donations', [ContactController::class, 'donationInfo'])->name('contacts.donations');

    // Réseaux sociaux des églises
    Route::get('contacts-social-media', [ContactController::class, 'socialMedia'])->name('contacts.social-media');

    // ========== VALIDATION ET MAINTENANCE ==========

    // Valider les URLs et emails
    Route::post('contacts/validate-links', [ContactController::class, 'validateLinks'])->name('contacts.validate-links');

    // Nettoyer les données obsolètes
    Route::post('contacts/cleanup', [ContactController::class, 'cleanup'])->name('contacts.cleanup');

    // Synchroniser avec services externes
    Route::post('contacts/sync-external', [ContactController::class, 'syncExternal'])->name('contacts.sync-external');
});

// ========== ROUTES API POUR LES CONTACTS ==========

Route::prefix('api/v1')->name('api.v1.')->middleware(['auth:sanctum'])->group(function () {

    // API CRUD complète
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::patch('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.patch');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // ========== API SPÉCIALISÉE ==========

    // Recherche et filtrage
    Route::get('contacts/search/nearby', [ContactController::class, 'searchNearby'])->name('contacts.search.nearby');
    Route::get('contacts/search/text', [ContactController::class, 'textSearch'])->name('contacts.search.text');
    Route::get('contacts/filter/advanced', [ContactController::class, 'advancedFilter'])->name('contacts.filter.advanced');

    // Statistiques et métriques
    Route::get('contacts-statistics', [ContactController::class, 'statistics'])->name('contacts.statistics');
    Route::get('contacts-metrics', [ContactController::class, 'metrics'])->name('contacts.metrics');

    // Actions en masse
    Route::post('contacts/bulk-actions', [ContactController::class, 'bulkActions'])->name('contacts.bulk-actions');
    Route::patch('contacts/{contact}/verify', [ContactController::class, 'verify'])->name('contacts.verify');

    // Export et synchronisation
    Route::get('contacts-export', [ContactController::class, 'export'])->name('contacts.export');
    Route::post('contacts-import', [ContactController::class, 'import'])->name('contacts.import');
    Route::post('contacts/sync', [ContactController::class, 'syncExternal'])->name('contacts.sync');

    // Géolocalisation
    Route::post('contacts/{contact}/geocode', [ContactController::class, 'geocodeManual'])->name('contacts.geocode');
    Route::get('contacts/geo/bounds', [ContactController::class, 'getGeoBounds'])->name('contacts.geo.bounds');

    // Utilitaires
    Route::get('contacts/{contact}/qr-code', [ContactController::class, 'generateQRCode'])->name('contacts.qr-code');
    Route::post('contacts/{contact}/duplicate', [ContactController::class, 'duplicate'])->name('contacts.duplicate');
    Route::post('contacts/validate-data', [ContactController::class, 'validateData'])->name('contacts.validate-data');
});

// ========== ROUTES PUBLIQUES (sans authentification) ==========

Route::name('public.')->group(function () {

    // Routes publiques pour affichage sur le site web

    // Liste publique des églises
    Route::get('eglises', [ContactController::class, 'publicDirectory'])->name('eglises.index');

    // Détails d'une église (vue publique)
    Route::get('eglises/{contact:slug}', [ContactController::class, 'publicShow'])->name('eglises.show');

    // Carte interactive publique
    Route::get('carte-eglises', [ContactController::class, 'publicMap'])->name('eglises.carte');

    // Recherche publique d'églises
    Route::get('eglises/recherche', [ContactController::class, 'publicSearch'])->name('eglises.recherche');

    // Églises par ville
    Route::get('eglises/ville/{ville}', [ContactController::class, 'publicByCity'])->name('eglises.ville');

    // Églises par dénomination
    Route::get('eglises/denomination/{denomination}', [ContactController::class, 'publicByDenomination'])->name('eglises.denomination');

    // Contact d'urgence (numéros d'urgence des églises)
    Route::get('urgence-eglises', [ContactController::class, 'publicEmergency'])->name('eglises.urgence');

    // Informations de dons publiques
    Route::get('eglises/{contact}/dons', [ContactController::class, 'publicDonationInfo'])->name('eglises.dons');

    // QR Code public d'une église
    Route::get('eglises/{contact}/qr', [ContactController::class, 'publicQRCode'])->name('eglises.qr');

    // vCard pour téléchargement
    Route::get('eglises/{contact}/vcard', [ContactController::class, 'downloadVCard'])->name('eglises.vcard');
});

// ========== ROUTES SPÉCIALISÉES POUR WEBHOOKS ET INTÉGRATIONS ==========

Route::prefix('webhooks')->name('webhooks.')->group(function () {

    // Webhook pour mise à jour depuis services externes
    Route::post('contacts/google-places', [ContactController::class, 'webhookGooglePlaces'])->name('contacts.google-places');

    // Webhook pour validation d'emails
    Route::post('contacts/email-validation', [ContactController::class, 'webhookEmailValidation'])->name('contacts.email-validation');

    // Webhook pour géocodage en lot
    Route::post('contacts/geocoding-batch', [ContactController::class, 'webhookGeocodingBatch'])->name('contacts.geocoding-batch');
});

// ========== ROUTES POUR INTÉGRATIONS MOBILES ==========

Route::prefix('mobile/v1')->name('mobile.v1.')->middleware(['auth:sanctum'])->group(function () {

    // Liste optimisée pour mobile
    Route::get('contacts/list', [ContactController::class, 'mobileList'])->name('contacts.list');

    // Recherche géolocalisée pour mobile
    Route::get('contacts/nearby', [ContactController::class, 'mobileNearby'])->name('contacts.nearby');

    // Détails optimisés pour mobile
    Route::get('contacts/{contact}/details', [ContactController::class, 'mobileDetails'])->name('contacts.details');

    // Directions vers une église
    Route::get('contacts/{contact}/directions', [ContactController::class, 'getDirections'])->name('contacts.directions');

    // Appel direct depuis l'app
    Route::post('contacts/{contact}/call-log', [ContactController::class, 'logPhoneCall'])->name('contacts.call-log');

    // Partage de contact
    Route::post('contacts/{contact}/share', [ContactController::class, 'shareContact'])->name('contacts.share');

    // Favoris membres
    Route::post('contacts/{contact}/favorite', [ContactController::class, 'toggleFavorite'])->name('contacts.favorite');
    Route::get('contacts/favorites', [ContactController::class, 'getUserFavorites'])->name('contacts.favorites');
});

// ========== ROUTES ADMINISTRATIVES AVANCÉES ==========

Route::prefix('admin')->name('admin.')->middleware(['auth', 'permission:contacts.admin'])->group(function () {

    // ========== AUDIT ET LOGS ==========

    // Audit et logs
    Route::get('contacts/audit-trail', [ContactController::class, 'auditTrail'])->name('contacts.audit');
    Route::get('contacts/{contact}/history', [ContactController::class, 'contactHistory'])->name('contacts.history');

    // ========== MAINTENANCE ET NETTOYAGE ==========

    // Maintenance et nettoyage
    Route::post('contacts/maintenance/cleanup-duplicates', [ContactController::class, 'cleanupDuplicates'])->name('contacts.cleanup-duplicates');
    Route::post('contacts/maintenance/fix-geocoding', [ContactController::class, 'fixGeocoding'])->name('contacts.fix-geocoding');
    Route::post('contacts/maintenance/validate-all', [ContactController::class, 'validateAllContacts'])->name('contacts.validate-all');

    // ========== IMPORT/EXPORT AVANCÉ ==========

    // Import/Export avancé
    Route::post('contacts/import/bulk', [ContactController::class, 'bulkImport'])->name('contacts.bulk-import');
    Route::get('contacts/export/full-backup', [ContactController::class, 'fullBackup'])->name('contacts.full-backup');

    // ========== SYNCHRONISATION AVEC SERVICES TIERS ==========

    // Synchronisation avec services tiers
    Route::post('contacts/sync/google-my-business', [ContactController::class, 'syncGoogleMyBusiness'])->name('contacts.sync-gmb');
    Route::post('contacts/sync/facebook-pages', [ContactController::class, 'syncFacebookPages'])->name('contacts.sync-facebook');

    // ========== RAPPORTS AVANCÉS ==========

    // Rapports avancés
    Route::get('contacts/reports/data-quality', [ContactController::class, 'dataQualityReport'])->name('contacts.data-quality');
    Route::get('contacts/reports/usage-analytics', [ContactController::class, 'usageAnalytics'])->name('contacts.usage-analytics');
});

// ========== DÉFINITION DES CONTRAINTES DE ROUTES ==========

// Contraintes pour s'assurer que les paramètres sont corrects
Route::pattern('contact', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('ville', '[a-zA-Z\-\s]+'); // Nom de ville
Route::pattern('denomination', '[a-zA-Z\-\s]+'); // Dénomination

/*
|--------------------------------------------------------------------------
| EXEMPLES D'UTILISATION DES ROUTES
|--------------------------------------------------------------------------
|
| ROUTES WEB PRINCIPALES :
| - GET  /private/contacts                        -> Liste des contacts
| - GET  /private/contacts/create                 -> Formulaire de création
| - POST /private/contacts                        -> Enregistrer nouveau contact
| - GET  /private/contacts/{id}                   -> Détails d'un contact
| - GET  /private/contacts/{id}/edit              -> Formulaire d'édition
| - PUT  /private/contacts/{id}                   -> Mettre à jour contact
| - DELETE /private/contacts/{id}                 -> Supprimer contact
|
| ROUTES SPÉCIALISÉES :
| - PATCH /private/contacts/{id}/verify           -> Vérifier un contact
| - POST  /private/contacts/bulk-actions          -> Actions en masse
| - GET   /private/contacts/search/nearby         -> Recherche géographique
| - GET   /private/contacts-statistics            -> Statistiques
| - GET   /private/contacts-export                -> Export des données
|
| ROUTES PUBLIQUES :
| - GET /eglises                                 -> Liste publique des églises
| - GET /eglises/{slug}                          -> Détails publics d'une église
| - GET /carte-eglises                           -> Carte interactive
| - GET /eglises/ville/{ville}                   -> Églises par ville
|
| ROUTES API :
| - GET    /api/v1/contacts                      -> API liste des contacts
| - POST   /api/v1/contacts                      -> API créer contact
| - GET    /api/v1/contacts/{id}                 -> API détails contact
| - PUT    /api/v1/contacts/{id}                 -> API modifier contact
| - DELETE /api/v1/contacts/{id}                 -> API supprimer contact
|
| ROUTES MOBILES :
| - GET /mobile/v1/contacts/nearby               -> Contacts proches (mobile)
| - GET /mobile/v1/contacts/favorites            -> Favoris membres
| - POST /mobile/v1/contacts/{id}/favorite       -> Ajouter/retirer favori
|
*/
