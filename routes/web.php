<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBI\DbiRequestController;
use App\Http\Controllers\Admin\RightsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TeamController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // DBI ROutes
    Route::resource('dbi-tool/dbi', DbiRequestController::class);

    // Rights
    Route::resource('/admin/rights', RightsController::class);

    // Roles
    Route::resource('/admin/roles', RoleController::class);

    // Teams
    Route::resource('teams', TeamController::class);

    // Admin  User CRUD operations
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin//users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles/{roleId}/rights', [UserController::class, 'getRightsForRole']);
    Route::get('/admin//users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.reset-password');
    Route::put('/admin//users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.update-password');
});


// Home Routes
Route::get('/home', function () {
    return view('home.index');
})->middleware(['auth', 'verified'])->name('home');



require __DIR__.'/auth.php';
