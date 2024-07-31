<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register dashboard routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "dashboard" middleware group. Make something great!
|
*/

Route::middleware('auth:admins')->group(function () {
    Route::get('/', \App\Http\Controllers\Dashboard\DashboardController::class)->name('index');

    /* --------- Operations Management --------- */
    Route::resource('operations',\App\Http\Controllers\Dashboard\OperationController::class);

    /* --------- Website Management --------- */
    Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);
    Route::get('users/operations/{id}', [\App\Http\Controllers\Dashboard\UserController::class,"showOperations"])->name("users.operations");
    
    Route::resource('devices', \App\Http\Controllers\Dashboard\DeviceController::class);
    Route::get('devices/data/{deviceID}', [\App\Http\Controllers\Dashboard\DeviceController::class,"getDeviceData"]);

    Route::resource('shops', \App\Http\Controllers\Dashboard\ShopsController::class);
    
    /* --------- Support Management --------- */
    Route::resource('support', \App\Http\Controllers\Dashboard\SupportController::class);
    /* --------- Prices Management --------- */
    Route::resource('prices', \App\Http\Controllers\PriceController::class);
    /* --------- Gifts Management --------- */
    Route::resource('gifts', \App\Http\Controllers\Dashboard\GiftsController::class);
    Route::get('gifts-show/{id}', [\App\Http\Controllers\Dashboard\GiftsController::class,'showGiftUsage'])->name('gifts-show');
    Route::get('gifts-deliver/{id}', [\App\Http\Controllers\Dashboard\GiftsController::class,'deliver'])->name('gifts-deliver');

    /* --------- Vouchers Management --------- */
    Route::resource('vouchers', \App\Http\Controllers\Dashboard\VoucherController::class);
    
    /* --------- Control Management --------- */
    Route::get('control',function(){ return view('dashboard.control.index'); })->name('control.index');
    Route::resource('powerbank', \App\Http\Controllers\Dashboard\PowerbanksController::class);
    Route::post('powerbank/eject', [\App\Http\Controllers\Dashboard\PowerbanksController::class,'eject'])->name('powerbank.eject');

    /* --------- Utilities --------- */
    Route::resource('memos', \App\Http\Controllers\Dashboard\MemoController::class);
    Route::resource('qr-code', \App\Http\Controllers\Dashboard\QrCodeController::class)->only(['index']);
    Route::resource('impersonate', \App\Http\Controllers\Dashboard\ImpersonateController::class)->only(['create', 'store']);

    /* --------- Settings --------- */
    Route::get('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('profile.update')->middleware('image.optimize');

    Route::resource('roles', \App\Http\Controllers\Dashboard\RoleController::class)->except(['show']);
    Route::resource('admins', \App\Http\Controllers\Dashboard\AdminController::class)->except(['show']);

    Route::get('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'update'])->name('settings.update');

    Route::resource('language', \App\Http\Controllers\Dashboard\LanguageController::class)->only(['index', 'edit', 'update']);
    Route::resource('language', \App\Http\Controllers\Dashboard\LanguageController::class)->only(['show'])->withoutMiddleware(['auth:admins']);
    Route::get('language/{locale}/sync', [\App\Http\Controllers\Dashboard\SyncLanguageController::class, 'update'])->name('language.sync');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your application. These
| routes are helpful when building the login and registration screens
| for your application.
|
*/

Route::withoutMiddleware(\App\Http\Middleware\Dashboard\RoutePermission::class)->group(function () {
    Route::middleware('guest:admins')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'store'])->name('login.store');

        Route::get('forgot-password', [\App\Http\Controllers\Dashboard\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [\App\Http\Controllers\Dashboard\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [\App\Http\Controllers\Dashboard\Auth\NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [\App\Http\Controllers\Dashboard\Auth\NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:admins')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});