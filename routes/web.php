<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBI\DbiRequestController;
use App\Http\Controllers\Admin\RightsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\PwgroupController;
use App\Http\Controllers\Admin\PwroleController;
use App\Http\Controllers\Admin\PwconnectController;
use App\Http\Controllers\Admin\DatabaseInfoController;
use App\Http\Controllers\DBI\MarketController;
use App\Http\Controllers\Admin\DbInstanceController;

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
    Route::get('/dbi-tool/dbi/{dbiRequest}/selectdb', [DbiRequestController::class, 'selectdb'])->name('dbi.selectdb');
    Route::put('/dbi-tool/dbi/{dbiRequest}/selectdb', [DbiRequestController::class, 'updateSelectDb'])->name('dbi.updateSelectDb');
    Route::get('/dbi-tool/dbi/{dbiRequest}/createsqlfile', [DbiRequestController::class, 'createsqlfile'])->name('dbi.createsqlfile');
    Route::get('/dbi-tool/dbi/{dbiRequestId}/additionalinfo', [DbiRequestController::class, 'additionalinfo'])->name('dbi.additionalinfo');
    Route::post('/dbi-tool/dbi/{dbiRequestId}/temporarytable', [DbiRequestController::class, 'storeTemporaryTable'])->name('dbi.storeTemporaryTable');
    Route::get('/dbi-tool/dbi/{dbiRequestId}/testDBI', [DbiRequestController::class, 'testDBI'])->name('dbi.testDBI');
    Route::post('/dbi-tool/dbi/{dbiRequest}/test', [DbiRequestController::class, 'testDbiQuery'])->name('dbi.testDbi');
    Route::post('/dbi-tool/dbi/get-db-user', [DbiRequestController::class, 'getDbUser'])->name('dbi.getDbUser');

    Route::post('/dbi-tool/dbi/{dbiRequest}/submit-to-sde', [DbiRequestController::class, 'submitToSDE'])->name('dbi.submitToSDE');
    Route::post('/dbi-tool/dbi/{dbiRequest}/sdeApprovedOrReject', [DbiRequestController::class, 'sdeApprovedOrReject'])->name('dbi.sdeApprovedOrReject');

    Route::post('/dbi-tool/dbi/{dbiRequest}/datApprovedOrReject', [DbiRequestController::class, 'datApprovedOrReject'])->name('dbi.datApprovedOrReject');
});

Route::middleware('is_admin')->group(function () {

    // Rights
    Route::resource('/admin/rights', RightsController::class);

    // Roles
    Route::resource('/admin/roles', RoleController::class);

    // Teams
    Route::resource('/admin/teams', TeamController::class);

    // User
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin//users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles/{roleId}/rights', [UserController::class, 'getRightsForRole']);
    Route::get('/admin//users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.reset-password');
    Route::put('/admin//users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.update-password');
    Route::post('/admin/users/assigned-users', [UserController::class, 'getAssignedUsers'])->name('users.assigned-users');

    // Markets
    Route::resource('/admin/markets', MarketController::class);

    // DB Instance
    Route::resource('/admin/db-instances', DbInstanceController::class);

    // Database Info
    Route::resource('/admin/database-info', DatabaseInfoController::class);

    // PW Groups
    Route::resource('/admin/pwgroups', PwgroupController::class);
    
    // PW Roles
    Route::resource('/admin/pwroles', PwroleController::class);

    // PW Connects
    Route::resource('/admin/pwconnects', PwconnectController::class);
    Route::get('/pwconnects/{pwconnect}/users', [PwconnectController::class, 'users'])->name('pwconnects.users');
    Route::post('/pwconnects/{pwconnect}/users/{user}', [PwconnectController::class, 'assignUser'])->name('pwconnects.assignUser');
    Route::delete('/pwconnects/{pwconnect}/users/{user}', [PwconnectController::class, 'removeUser'])->name('pwconnects.removeUser');
    
    Route::get('/pwconnects/{pwconnect}/roles', [PwconnectController::class, 'roles'])->name('pwconnects.roles');
    Route::post('/pwconnects/{pwconnect}/roles/{role}', [PwconnectController::class, 'assignRole'])->name('pwconnects.assignRole');
    Route::delete('/pwconnects/{pwconnect}/roles/{role}', [PwconnectController::class, 'removeRole'])->name('pwconnects.removeRole');

    Route::get('/admin/pwgroups/{pwgroup}/change-users', [PwgroupController::class, 'changeUsers'])->name('pwgroups.changeUsers');
    Route::post('/admin/pwgroups/{pwgroup}/users/{user}', [PwgroupController::class, 'addUser'])->name('pwgroups.addUser');
    Route::delete('/admin/pwgroups/{pwgroup}/users/{user}', [PwgroupController::class, 'removeUser'])->name('pwgroups.removeUser');

});


// Home Routes
Route::get('/home', function () {
    return view('home.index');
})->middleware(['auth', 'verified'])->name('home');



require __DIR__.'/auth.php';
