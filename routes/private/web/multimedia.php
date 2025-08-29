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

Route::prefix('dashboard/multimedia')->name('private.multimedia.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes de base CRUD
    Route::get('/', [MultimediaController::class, 'index'])->name('index');
    Route::get('create', [MultimediaController::class, 'create'])->name('create');
        // Galerie et vues spéciales
    Route::get('/publique', [MultimediaController::class, 'galerie'])->name('galerie');
    Route::get('/statistiques', [MultimediaController::class, 'statistiques'])->name('statistiques');

    Route::post('/', [MultimediaController::class, 'store'])->name('store');
    Route::get('{multimedia}', [MultimediaController::class, 'show'])->name('show');
    Route::get('{multimedia}/edit', [MultimediaController::class, 'edit'])->name('edit');
    Route::put('{multimedia}', [MultimediaController::class, 'update'])->name('update');
    Route::patch('{multimedia}', [MultimediaController::class, 'update'])->name('update.patch');
    Route::delete('{multimedia}', [MultimediaController::class, 'destroy'])->name('destroy');

    // Routes spécifiques aux médias
    Route::get('{multimedia}/download', [MultimediaController::class, 'download'])->name('download');
    Route::patch('{multimedia}/approve', [MultimediaController::class, 'approve'])->name('approve');
    Route::patch('{multimedia}/reject', [MultimediaController::class, 'reject'])->name('reject');
    Route::patch('{multimedia}/toggle-featured', [MultimediaController::class, 'toggleFeatured'])->name('toggle-featured');



    // Modération en lot
    Route::post('moderation/bulk', [MultimediaController::class, 'bulkModerate'])->name('bulk-moderate');

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
Route::prefix('dashboard/multimedia')->name('private.multimedia.')->middleware(['auth', 'permission:moderate_media'])->group(function () {

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
Route::prefix('dashboard/multimedia/admin')->name('private.multimedia.admin.')->middleware(['auth', 'permission:manage_media'])->group(function () {

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
| Routes pour l'upload via AJAX/API
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard/multimedia/upload')->name('private.multimedia.upload.')->middleware(['auth'])->group(function () {

    // Upload simple
    Route::post('single', [MultimediaController::class, 'store'])->name('single');

    // Upload multiple (chunk par chunk)
    Route::post('chunk', function (Request $request) {
        // Logique pour l'upload par chunks
        return response()->json(['success' => true, 'chunk_uploaded' => true]);
    })->name('chunk');

    // Finaliser upload multiple
    Route::post('finalize', function (Request $request) {
        // Logique pour finaliser l'upload multiple
        return response()->json(['success' => true, 'files_processed' => $request->file_count]);
    })->name('finalize');

});

/*
|--------------------------------------------------------------------------
| Routes pour les événements spécifiques
|--------------------------------------------------------------------------
*/

// Routes pour associer des médias à des événements
Route::prefix('dashboard')->middleware(['auth'])->group(function () {

    // Médias d'un culte
    Route::get('cultes/{culte}/multimedia', function (Request $request, $culteId) {
        $request->merge(['culte_id' => $culteId]);
        return app(MultimediaController::class)->index($request);
    })->name('private.cultes.multimedia');

    // Médias d'un événement
    Route::get('events/{event}/multimedia', function (Request $request, $eventId) {
        $request->merge(['event_id' => $eventId]);
        return app(MultimediaController::class)->index($request);
    })->name('private.events.multimedia');

    // Médias d'une intervention
    Route::get('interventions/{intervention}/multimedia', function (Request $request, $interventionId) {
        $request->merge(['intervention_id' => $interventionId]);
        return app(MultimediaController::class)->index($request);
    })->name('private.interventions.multimedia');

    // Médias d'une réunion
    Route::get('reunions/{reunion}/multimedia', function (Request $request, $reunionId) {
        $request->merge(['reunion_id' => $reunionId]);
        return app(MultimediaController::class)->index($request);
    })->name('private.reunions.multimedia');

});

/*
|--------------------------------------------------------------------------
| Routes pour les flux RSS et sitemaps
|--------------------------------------------------------------------------
*/

Route::prefix('feeds')->group(function () {

    // RSS des derniers médias
    Route::get('multimedia/rss', function (Request $request) {
        $medias = DB::table('galerie_publique')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();

        return response()->view('feeds.multimedia-rss', compact('medias'))
                       ->header('Content-Type', 'application/rss+xml');
    })->name('multimedia.rss');

    // Sitemap des médias
    Route::get('multimedia/sitemap.xml', function (Request $request) {
        $medias = DB::table('galerie_publique')
                    ->select('slug', 'updated_at')
                    ->get();

        return response()->view('feeds.multimedia-sitemap', compact('medias'))
                       ->header('Content-Type', 'application/xml');
    })->name('multimedia.sitemap');

});

/*
|--------------------------------------------------------------------------
| Routes de recherche avancée
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard/multimedia/search')->name('private.multimedia.search.')->middleware(['auth'])->group(function () {

    // Recherche par tags
    Route::get('tags/{tag}', function (Request $request, $tag) {
        $request->merge(['search' => $tag]);
        return app(MultimediaController::class)->index($request);
    })->name('tag');

    // Recherche par photographe
    Route::get('photographer/{photographer}', function (Request $request, $photographer) {
        $request->merge(['photographe' => $photographer]);
        return app(MultimediaController::class)->index($request);
    })->name('photographer');

    // Recherche par date
    Route::get('date/{date}', function (Request $request, $date) {
        // Logique de recherche par date
        return app(MultimediaController::class)->index($request);
    })->name('date');

});

/*
|--------------------------------------------------------------------------
| Routes pour les widgets et intégrations
|--------------------------------------------------------------------------
*/

Route::prefix('widgets/multimedia')->name('widgets.multimedia.')->group(function () {

    // Widget derniers médias
    Route::get('recent', function (Request $request) {
        $medias = DB::table('medias_recents')->limit(6)->get();
        return response()->json(['medias' => $medias]);
    })->name('recent');

    // Widget médias populaires
    Route::get('popular', function (Request $request) {
        $medias = Multimedia::visible()
                           ->approuve()
                           ->public()
                           ->popular()
                           ->limit(6)
                           ->get();
        return response()->json(['medias' => $medias]);
    })->name('popular');

    // Widget slideshow
    Route::get('slideshow', function (Request $request) {
        $medias = Multimedia::featured()
                           ->visible()
                           ->approuve()
                           ->public()
                           ->ofType('image')
                           ->limit(5)
                           ->get();
        return response()->json(['medias' => $medias]);
    })->name('slideshow');

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

/*
|--------------------------------------------------------------------------
| Routes de maintenance et utilitaires
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard/multimedia/maintenance')->name('private.multimedia.maintenance.')->middleware(['auth', 'role:admin'])->group(function () {

    // Régénérer les miniatures
    Route::post('regenerate-thumbnails', function (Request $request) {
        // Logique pour régénérer toutes les miniatures
        return response()->json(['success' => true, 'message' => 'Miniatures régénérées']);
    })->name('regenerate.thumbnails');

    // Recalculer les hash des fichiers
    Route::post('recalculate-hashes', function (Request $request) {
        // Logique pour recalculer les hash
        return response()->json(['success' => true, 'message' => 'Hash recalculés']);
    })->name('recalculate.hashes');

    // Migration de stockage
    Route::post('migrate-storage', function (Request $request) {
        // Logique pour migrer le stockage
        return response()->json(['success' => true, 'message' => 'Migration effectuée']);
    })->name('migrate.storage');

});

/*
|--------------------------------------------------------------------------
| Routes de debug et développement (uniquement en mode debug)
|--------------------------------------------------------------------------
*/

if (config('app.debug')) {
    Route::prefix('debug/multimedia')->name('debug.multimedia.')->middleware(['auth', 'role:admin'])->group(function () {

        // Informations de debug sur un média
        Route::get('{multimedia}/debug', function ($multimediaId) {
            $multimedia = Multimedia::with(['culte', 'event', 'intervention', 'reunion'])->findOrFail($multimediaId);
            return response()->json([
                'multimedia' => $multimedia,
                'file_exists' => Storage::disk('public')->exists($multimedia->chemin_fichier),
                'file_size_disk' => Storage::disk('public')->size($multimedia->chemin_fichier),
                'metadata' => $multimedia->metadonnees_exif
            ]);
        })->name('debug');

        // Test upload
        Route::get('test-upload', function () {
            return view('debug.multimedia-upload-test');
        })->name('test.upload');

    });
}
