<?php

use App\Enums\DocumentType;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LocalAdminLoginController;
use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Complaint\ComplaintDocumentController;
use App\Http\Controllers\Complaint\ComplaintWorkflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil publique (welcome page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Routes Utilisateurs Authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Page d'accueil pour tous
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    // Route plainte en paramètres
    Route::middleware(['can:viewAny,App\Models\Complaint'])->group(function () {
        Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    });

    Route::middleware(['can:create,App\Models\Complaint'])->group(function () {
        Route::resource('complaints', ComplaintController::class)->only(['create', 'store']);
    });

    // Routes avec plainte en paramètres
    Route::middleware(['can:view,complaint'])->group(function () {
        Route::get('complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    });

    Route::middleware(['can:update,complaint'])->group(function () {
        Route::resource('complaints', ComplaintController::class)->only(['edit', 'update']);
    });

    Route::middleware(['can:delete,complaint'])->group(function () {
        Route::resource('complaints', ComplaintController::class)->only(['destroy']);
    });

    // Routes qui concernent les relations avec les plaintes (gestion documentaire)
    Route::prefix('complaints/{complaint}')->group(function () {


        // Assigner une plainte à un département
        Route::post('/assign', [ComplaintWorkflowController::class, 'assign'])
            ->name('complaints.assign');

        // Reassigner une plainte à un département
        Route::post('/reassign', [ComplaintWorkflowController::class, 'reassign'])
            ->name('complaints.reassign');

        // Accuser réception d'une plainte
        Route::post('/acknowledge', [ComplaintWorkflowController::class, 'acknowledge'])
            ->name('complaints.acknowledge');

        // Évaluer la plainte (recevable et fondée)
        Route::post('/evaluate', [ComplaintWorkflowController::class, 'evaluate'])
            ->name('complaints.evaluate');

        // Répondre à une plainte
        Route::post('/respond', [ComplaintWorkflowController::class, 'respond'])
            ->name('complaints.respond');

        // Clôturer une plainte
        Route::post('/close', [ComplaintWorkflowController::class, 'close'])
            ->name('complaints.close');

        Route::get('/pdf/{type}', [ComplaintDocumentController::class, 'generate'])
            ->name('documents.pdf');
    });

    // Historique
    Route::get('/history')
        ->name('history');

    // Notifications
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');

    // Aide
    Route::get('/help', function () {
        return view('help');
    })->name('help');

    // Profil
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');

    // Paramètres
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Mises à jour
    Route::get('/updates', function () {
        return view('updates');
    })->name('updates');
});
