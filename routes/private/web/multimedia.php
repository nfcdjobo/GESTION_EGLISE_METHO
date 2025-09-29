<?php

use App\Models\Multimedia;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Private\Web\MultimediaController;


/*
|--------------------------------------------------------------------------
| Routes Web et API pour les Médias
|--------------------------------------------------------------------------
|
| Routes pour la gestion de la galerie multimédia avec upload,
| modération et accès public/privé
|
*/

Route::prefix('multimedia')->name('private.multimedia.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes de base CRUD
    Route::get('/', [MultimediaController::class, 'index'])->middleware('permission:multimedia.read')->name('index');
    Route::get('create', [MultimediaController::class, 'create'])->middleware('permission:multimedia.create')->name('create');
    // Galerie et vues spéciales
    Route::get('/publique', [MultimediaController::class, 'galerie'])->middleware('permission:multimedia.read')->name('galerie');
    Route::get('/statistiques', [MultimediaController::class, 'statistiques'])->middleware('permission:multimedia.statistics')->name('statistiques');

    Route::post('/', [MultimediaController::class, 'store'])->middleware('permission:multimedia.create')->name('store');
    Route::get('{multimedia}', [MultimediaController::class, 'show'])->middleware('permission:multimedia.read')->name('show');
    Route::get('{multimedia}/edit', [MultimediaController::class, 'edit'])->middleware('permission:multimedia.update')->name('edit');
    Route::put('{multimedia}', [MultimediaController::class, 'update'])->middleware('permission:multimedia.update')->name('update');
    Route::patch('{multimedia}', [MultimediaController::class, 'update'])->middleware('permission:multimedia.update')->name('update.patch');
    Route::delete('{multimedia}', [MultimediaController::class, 'destroy'])->middleware('permission:multimedia.delete')->name('destroy');

    // Routes spécifiques aux médias
    Route::get('{multimedia}/download', [MultimediaController::class, 'download'])->middleware('permission:multimedia.download')->name('download');
    Route::patch('{multimedia}/approve', [MultimediaController::class, 'approve'])->middleware('permission:multimedia.approve')->name('approve');
    Route::patch('{multimedia}/reject', [MultimediaController::class, 'reject'])->middleware('permission:multimedia.reject')->name('reject');
    Route::patch('{multimedia}/toggle-featured', [MultimediaController::class, 'toggleFeatured'])->middleware('permission:multimedia.toggle-featured')->name('toggle-featured');



    // Modération en lot
    Route::post('moderation/bulk', [MultimediaController::class, 'bulkModerate'])->middleware('permission:multimedia.update')->name('bulk-moderate');

});

/*
|--------------------------------------------------------------------------
| Routes publiques pour la galerie
|--------------------------------------------------------------------------
*/

Route::prefix('galerie')->name('public.multimedia.')->group(function () {

    // Galerie publique accessible sans connexion
    Route::get('/', [MultimediaController::class, 'galerie'])->name('index');
    Route::get('media/{multimedia}', [MultimediaController::class, 'show'])->name('show');

    // Routes par catégorie
    Route::get('photos', function (Request $request) {
        $request->merge(['type_media' => 'image']);
        return app(MultimediaController::class)->galerie($request);
    })->name('photos');

    Route::get('videos', function (Request $request) {
        $request->merge(['type_media' => 'video']);
        return app(MultimediaController::class)->galerie($request);
    })->name('videos');

    Route::get('audios', function (Request $request) {
        $request->merge(['type_media' => 'audio']);
        return app(MultimediaController::class)->galerie($request);
    })->name('audios');

});

/*
|--------------------------------------------------------------------------
| Routes avec middleware de permissions spécifiques
|--------------------------------------------------------------------------
*/

// Routes de modération (réservées aux modérateurs)
Route::prefix('multimedia')->name('private.multimedia.')->middleware(['auth', 'permission:moderate_media'])->group(function () {

    Route::get('moderation/queue', function (Request $request) {
        $request->merge(['statut_moderation' => 'en_attente']);
        return app(MultimediaController::class)->index($request);
    })->name('moderation.queue');

    Route::get('moderation/rejected', function (Request $request) {
        $request->merge(['statut_moderation' => 'rejete']);
        return app(MultimediaController::class)->index($request);
    })->name('moderation.rejected');

});

// Routes de gestion avancée (administrateurs)
Route::prefix('multimedia/admin')->name('private.multimedia.admin.')->middleware(['auth', 'permission:manage_media'])->group(function () {

    Route::get('dashboard', [MultimediaController::class, 'statistiques'])->name('dashboard');

    // Gestion du stockage
    Route::post('storage/cleanup', function (Request $request) {
        // Logique pour nettoyer les fichiers orphelins
        return response()->json(['success' => true, 'message' => 'Nettoyage effectué']);
    })->name('storage.cleanup');

    Route::post('storage/optimize', function (Request $request) {
        // Logique pour optimiser le stockage
        return response()->json(['success' => true, 'message' => 'Optimisation effectuée']);
    })->name('storage.optimize');

});

/*
|--------------------------------------------------------------------------
| Routes API pour intégrations externes
|--------------------------------------------------------------------------
*/

Route::prefix('api/v1/multimedia')->name('api.multimedia.')->middleware(['auth:api'])->group(function () {

    // API Resource standard
    Route::apiResource('media', MultimediaController::class)->parameters(['media' => 'multimedia']);

    // Endpoints API spécialisés
    Route::post('upload', [MultimediaController::class, 'store'])->name('upload');
    Route::get('gallery/public', [MultimediaController::class, 'galerie'])->name('gallery.public');
    Route::get('stats', [MultimediaController::class, 'statistiques'])->name('statistics');

    // API de modération
    Route::post('moderate/bulk', [MultimediaController::class, 'bulkModerate'])->name('moderate.bulk');
    Route::patch('{multimedia}/approve', [MultimediaController::class, 'approve'])->name('approve');
    Route::patch('{multimedia}/reject', [MultimediaController::class, 'reject'])->name('reject');

});




/*
|--------------------------------------------------------------------------
| Contraintes de paramètres
|--------------------------------------------------------------------------
*/

// Pattern pour les UUIDs
Route::pattern('multimedia', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('culte', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('event', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('intervention', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('reunion', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
