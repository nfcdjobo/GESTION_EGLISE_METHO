<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\UserController;
use App\Http\Controllers\Private\Web\CulteController;
use App\Http\Controllers\Private\Web\ErrorController;
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

require __DIR__.'/auth/index.php';

Route::get('/', function () {
    return view('index');
})->name('public.accueil');

Route::get('/donate', function () {
    return view('components.public.donate');
})->name('public.add-donate');


Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/periode', [DashboardController::class, 'getStatistiquesPeriode'])->name('getStatistiquesPeriode');
});

Route::fallback([ErrorController::class, 'notFound'])->name('errors.404');






