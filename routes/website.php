<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function(){ return redirect()->route('dashboard.index'); })->name('index');
Route::resource('language', \App\Http\Controllers\Website\LanguageController::class)->only(['show']);
Route::get('/payment/response', function(Request $request){
    $view = "payment.paymobPayementResponse";

    if ($request->gateway) {
        if ($request->gateway == "paymob") {
            $view = "payment.paymobPayementResponse";
        } elseif ($request->gateway == "fawry") {
            $view = "payment.fawryPayementResponse";
        }
    }
    // switch ($_GET['gateway']) {
    //     case 'paymob':
    //         $view = "payment.paymobPayementResponse";
    //         break;
    //     case 'fawry':
    //         $view = "payment.fawryPayementResponse";
    //         break;
    //     default:
    //         $view = "payment.paymobPayementResponse";
    //         break;
    // }
    return  view($view);
})->name('payment-response');
Route::get('/test', function(){ return  view("payment.test");});
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

// Route::middleware('guest:users')->group(function () {
//     Route::get('/login', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('/login', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'store'])->name('login.store');

//     Route::get('/register', [\App\Http\Controllers\Website\Auth\RegisteredUserController::class, 'create'])->name('register');
//     Route::post('/register', [\App\Http\Controllers\Website\Auth\RegisteredUserController::class, 'store'])->name('register.store');

//     Route::get('forgot-password', [\App\Http\Controllers\Website\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
//     Route::post('forgot-password', [\App\Http\Controllers\Website\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

//     Route::get('reset-password/{token}', [\App\Http\Controllers\Website\Auth\NewPasswordController::class, 'create'])->name('password.reset');
//     Route::post('reset-password', [\App\Http\Controllers\Website\Auth\NewPasswordController::class, 'store'])->name('password.store');
// });

// Route::middleware('auth:users')->group(function () {
//     Route::get('verify-email', \App\Http\Controllers\Website\Auth\EmailVerificationPromptController::class)->name('verification.notice');
//     Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Website\Auth\VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
//     Route::post('email/verification-notification', [\App\Http\Controllers\Website\Auth\EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

//     Route::post('/logout', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
// });
