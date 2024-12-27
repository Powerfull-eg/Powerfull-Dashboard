<?php

use App\Http\Controllers\Dashboard\DeviceController;
use App\Http\Controllers\Dashboard\DeviceProviders\BajieController;
use App\Http\Controllers\Dashboard\ShopsController;
use App\Http\Controllers\Dashboard\NoteController;
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
    // Route::get('/test', [App\Jobs\CompleteFailedPayment::class,'handle']);
    
    Route::get('/', \App\Http\Controllers\Dashboard\DashboardController::class)->name('index');

    /* --------- Operations Management --------- */
    Route::resource('operations',\App\Http\Controllers\Dashboard\OperationController::class);
    Route::get('operation/{id}',[\App\Http\Controllers\Dashboard\OperationController::class,'getOperationData']);
    Route::post('operations/restore/{id}',[\App\Http\Controllers\Dashboard\OperationController::class,'restore'])->name('operations.restore');
    Route::post('operations/close/{id}',[\App\Http\Controllers\Dashboard\OperationController::class,'closeOrder'])->name('operations.close');
    Route::post('operations/refund/{id}',[\App\Http\Controllers\Dashboard\OperationController::class,'refundOrder'])->name('operations.refund');

    /* --------- Website Management --------- */
    Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);
    Route::get('users/operations/{id}', [\App\Http\Controllers\Dashboard\UserController::class,"showOperations"])->name("users.operations");
    Route::get('users/restore/{id}', [\App\Http\Controllers\Dashboard\UserController::class,"restore"])->name("users.restore");
    Route::post('users/block/{id}', [\App\Http\Controllers\Dashboard\UserController::class,"block"])->name("users.block");
    Route::post('users/unblock/{id}', [\App\Http\Controllers\Dashboard\UserController::class,"unblock"])->name("users.unblock");
    Route::post('users/reset-password/{id}',[\App\Http\Controllers\Dashboard\UserController::class,"resetPassword"])->name('users.reset-password');
    Route::get('users/add-gift/{id}',[\App\Http\Controllers\Dashboard\UserController::class,"addGift"])->name('users.gifts.create');
    Route::post('users/store-gift/{id}',[\App\Http\Controllers\Dashboard\UserController::class,"storeGift"])->name('users.gifts.store');

    Route::resource('devices', \App\Http\Controllers\Dashboard\DeviceController::class);
    Route::get('devices/data/{deviceID}', [\App\Http\Controllers\Dashboard\DeviceController::class,"getDeviceData"]);

    Route::resource('shops', \App\Http\Controllers\Dashboard\ShopsController::class);
    Route::get('shops/operations/{id}', [\App\Http\Controllers\Dashboard\ShopsController::class,'operations'])->name('shops.operations');
    Route::post('shops/sync', [\App\Http\Controllers\Dashboard\ShopsController::class,'syncShopData'])->name('shops.sync');
    // Shops Types
    Route::resource('shop-types', \App\Http\Controllers\Dashboard\ShopTypesController::class);
    
    /* --------- Support Management --------- */
    Route::resource('support', \App\Http\Controllers\Dashboard\SupportController::class);
    Route::post('support/message/update', [\App\Http\Controllers\Dashboard\SupportController::class,'updateMessage'])->name('support.message.update');
    Route::post('support/close/{id}', [\App\Http\Controllers\Dashboard\SupportController::class,'endTicket'])->name('support.close');
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
    Route::get('roles/update-permissions', [\App\Http\Controllers\Dashboard\RoleController::class, 'updatePermissionsRoute'])->name('roles.update-permissions');
    
    Route::resource('admins', \App\Http\Controllers\Dashboard\AdminController::class)->except(['show']);
    Route::post('admins/create-with-permissions', [\App\Http\Controllers\Dashboard\AdminController::class, 'createWithPermissions'])->name('admins.create-with-permissions');

    Route::get('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'update'])->name('settings.update');

    Route::resource('language', \App\Http\Controllers\Dashboard\LanguageController::class)->only(['index', 'edit', 'update']);
    Route::resource('language', \App\Http\Controllers\Dashboard\LanguageController::class)->only(['show'])->withoutMiddleware(['auth:admins']);
    Route::get('language/{locale}/sync', [\App\Http\Controllers\Dashboard\SyncLanguageController::class, 'update'])->name('language.sync');
    
    // payment routes
    Route::resource('payments', \App\Http\Controllers\Dashboard\PaymentController::class)->only(['create', 'store']);
    Route::post('payments/requestMultiplePayments', [\App\Http\Controllers\Dashboard\PaymentController::class,"requestMultiplePayments"])->name("payments.request-multiple-payments");
    Route::post('payments/incomplete/settings', [\App\Http\Controllers\Dashboard\PaymentController::class, 'incompletePaymentSettingsUpdate'])->name('payments.incomplete.settings');
    Route::post('payments/incomplete/edit-amount', [\App\Http\Controllers\Dashboard\PaymentController::class, 'editIncompleteOrderAmount'])->name('payments.incomplete.edit-amount');
    Route::post('payments/refund/', [\App\Http\Controllers\Dashboard\PaymentController::class, 'refund'])->name('payments.refund');
    
    // Device Routes
    Route::group(['middleware' => 'auth:admins'], function () {
        Route::get('/device-data/{deviceID}', [DeviceController::class,'getDeviceData'])->name('device-data');
        Route::post('/device-operation', [DeviceController::class,'deviceOperation'])->name('device-operation');
        Route::get('/slots', [DeviceController::class,'getSlotsInfo'])->name('slots');
        Route::post('/eject-battery', [DeviceController::class,'ejectPowerbank'])->name('eject-battery');
    });

    // Shop routes
    Route::post('updatemenu/{id}', [ShopsController::class,'updateShopMenu'])->name('update-menu');

    // Notes Routes
    Route::resource('notes', NoteController::class);
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