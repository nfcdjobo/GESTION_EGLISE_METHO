<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\UserController;
use App\Http\Controllers\Private\Web\CulteController;
use App\Http\Controllers\Private\Web\EventController;
use App\Http\Controllers\Private\Web\ClasseController;
use App\Http\Controllers\Private\Web\ProjetController;
use App\Http\Controllers\Private\Web\AnnonceController;
use App\Http\Controllers\Private\Web\ContactController;
use App\Http\Controllers\Private\Web\RapportController;
use App\Http\Controllers\Private\Web\ReunionController;
use App\Http\Controllers\Private\Web\DashboardController;
use App\Http\Controllers\Private\Web\ProgrammeController;
use App\Http\Controllers\Private\Web\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__.'/router/about.php';
require __DIR__.'/router/contact.php';
require __DIR__.'/router/events.php';
require __DIR__.'/router/culte.php';
require __DIR__.'/router/horaires.php';

require __DIR__.'/private/web/roles.php';
require __DIR__.'/private/web/permissions.php';
require __DIR__.'/private/web/user.php';
require __DIR__.'/private/web/classes.php';
require __DIR__.'/private/web/contacts.php';
require __DIR__.'/private/web/auditlog.php';
require __DIR__.'/private/web/programmes.php';
require __DIR__.'/auth/index.php';

Route::get('/', function () {
    return view('index');
})->name('public.accueil');

Route::get('dashboard', [DashboardController::class, 'index'])->name('private.dashboard');





// Routes pour le dashboard (à ajouter dans web.php)

Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('private.dashboard');

    // API pour les données de graphiques (AJAX)
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
        ->name('dashboard.chart-data');

    // Routes pour les autres modules (exemples)
    Route::prefix('admin')->name('admin.')->group(function () {

        // Membres/Utilisateurs
        Route::resource('users', UserController::class);

        // Cultes
        Route::resource('cultes', CulteController::class);

        // Événements
        Route::resource('events', EventController::class);

        // Réunions
        Route::resource('reunions', ReunionController::class);

        // Transactions spirituelles
        Route::resource('transactions', TransactionController::class);

        // Projets
        Route::resource('projets', ProjetController::class);

        // Annonces
        Route::resource('annonces', AnnonceController::class);

        // Programmes
        Route::resource('programmes', ProgrammeController::class);

        // Classes
        Route::resource('classes', ClasseController::class);

        // Contacts
        Route::resource('contacts', ContactController::class);

        // Rapports
        Route::get('rapports', [RapportController::class, 'index'])
            ->name('rapports.index');
        Route::get('rapports/financier', [RapportController::class, 'financier'])
            ->name('rapports.financier');
        Route::get('rapports/membres', [RapportController::class, 'membres'])
            ->name('rapports.membres');
        Route::get('rapports/activites', [RapportController::class, 'activites'])
            ->name('rapports.activites');
    });

    // Raccourcis directs pour le dashboard (sans préfixe admin)
    Route::get('/reunions', [ReunionController::class, 'index'])
        ->name('reunions.index');
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::get('/projets', [ProjetController::class, 'index'])
        ->name('projets.index');
    Route::get('/annonces', [AnnonceController::class, 'index'])
        ->name('annonces.index');

    // APIs pour les widgets du dashboard
    Route::prefix('api/dashboard')->name('api.dashboard.')->group(function () {
        Route::get('stats', [DashboardController::class, 'getStats']);
        Route::get('recent-activities', [DashboardController::class, 'getRecentActivities']);
        Route::get('notifications', [DashboardController::class, 'getNotifications']);
    });
});

// // Route de redirection par défaut vers le dashboard pour les utilisateurs connectés
// Route::get('/home', function () {
//     return redirect()->route('private.dashboard');
// });

// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route('private.dashboard');
//     }
//     return view('welcome'); // ou votre page d'accueil publique
// });


