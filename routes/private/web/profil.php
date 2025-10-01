<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ProfilController;

Route::middleware(['auth', 'user.status'])->prefix('profil')->name('private.profil.')->group(function () {
    Route::get('/', [ProfilController::class, 'index'])->name('index');
    Route::get('/editer', [ProfilController::class, 'edit'])->name('edit');
    Route::put('/informations', [ProfilController::class, 'updateInformations'])->name('update.informations');
    Route::get('/mot-de-passe', [ProfilController::class, 'editPassword'])->name('edit.password');
    Route::put('/mot-de-passe', [ProfilController::class, 'updatePassword'])->name('update.password');
    Route::delete('/photo', [ProfilController::class, 'deletePhoto'])->name('delete.photo');
    Route::get('/spirituel', [ProfilController::class, 'showSpirituel'])->name('spirituel');
    Route::put('/spirituel', [ProfilController::class, 'updateSpirituel'])->name('update.spirituel');
});
