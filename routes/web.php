<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman Data Dosen (Bisa diakses Admin & Mahasiswa)
    Route::get('/', [DosenController::class, 'index'])->name('home');
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');

    // CRUD Dosen (Hanya bisa diakses oleh Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
        Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
        Route::get('/dosen/{dosen}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
        Route::put('/dosen/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
        Route::delete('/dosen/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');
    });

    // Team Routes (Starter Kit)
    Route::prefix('{current_team}')
        ->middleware(['verified', EnsureTeamMembership::class])
        ->group(function () {
            Route::get('dashboard', DashboardController::class)->name('dashboard');
        });

    Route::post('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

require __DIR__.'/settings.php';
