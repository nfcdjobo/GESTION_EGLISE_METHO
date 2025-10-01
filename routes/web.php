<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ErrorController;
use App\Http\Controllers\Private\Web\DashboardController;





require __DIR__.'/private/web/roles.php';
require __DIR__.'/private/web/permissions.php';
require __DIR__.'/private/web/user.php';
require __DIR__.'/private/web/classes.php';
require __DIR__.'/private/web/contacts.php';
require __DIR__.'/private/web/auditlog.php';
require __DIR__.'/private/web/programmes.php';
require __DIR__.'/private/web/cultes.php';
require __DIR__.'/private/web/participantcultes.php';
require __DIR__.'/private/web/events.php';
require __DIR__.'/private/web/fonds.php';
require __DIR__.'/private/web/projets.php';

require __DIR__.'/private/web/typesreunions.php';
require __DIR__.'/private/web/reunions.php';
require __DIR__.'/private/web/rapportsreunions.php';
require __DIR__.'/private/web/annonces.php';
require __DIR__.'/private/web/interventions.php';
require __DIR__.'/private/web/multimedia.php';
require __DIR__.'/private/web/fimecos.php';
require __DIR__.'/private/web/moissons.php';
require __DIR__.'/private/web/assiduite-faible.php';
require __DIR__.'/private/web/parametres.php';
require __DIR__.'/private/web/parametresdons.php';
require __DIR__.'/private/web/profil.php';
require __DIR__.'/private/web/dons.php';
require __DIR__.'/public/web/donates.php';
require __DIR__.'/public/web/welcome.php';

require __DIR__.'/auth/index.php';








Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/export', [DashboardController::class, 'exporte'])->name('dashboard.exporte');

     Route::get('/export', [DashboardController::class, 'exporte'])->name('dashboard.exporte');

    Route::get('/periode', [DashboardController::class, 'getStatistiquesPeriode'])->name('getStatistiquesPeriode');
});

Route::fallback([ErrorController::class, 'notFound'])->name('errors.404');






