<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BajieController;
use App\Http\Controllers\Api\HelpController;
use App\Http\Controllers\Api\PaymobController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\Api\ApiVoucherController;
use App\Http\Controllers\Api\FawryPayController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\Api\PushTokensController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\Api\ShopsController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/get-saved-tokens', [\App\Http\Controllers\Api\FawryPayController::class,'getUserSavedTokens']);
Route::post('/delete-saved-tokens', [\App\Http\Controllers\Api\FawryPayController::class,'deleteUserSavedToken']);
Route::post('/fawry-payment-response', [\App\Http\Controllers\Api\FawryPayController::class,'fawryIframeReturn']);
Route::post('/fawry-notification', [\App\Http\Controllers\Api\FawryPayController::class,'fawryNotification']);
Route::post('/pay-with-saved-token', [\App\Http\Controllers\Api\FawryPayController::class,'payWithSavedToken']);
Route::post('/get-payment-data', [\App\Http\Controllers\Api\FawryPayController::class,'getPaymentData']);
Route::post('/refund', [\App\Http\Controllers\Api\FawryPayController::class,'refund']);
Route::post('/get-refrence-number',[FawryPayController::class,'createRefrenceNumber']);
Route::post('/get-qr-code',[FawryPayController::class,'createQrCode']);

// Connection Check
Route::get('connection', function () {
    return;
});

// App general settings 
Route::get('settings', function () {
    return response()->json([
        "map" => [
                "lat" => 30.222656,
                "lng" => 31.477425,
                "zoom" => 10,
                "mapId" => "a55a8dd1e435899e"

            ],
        "maintenance" => false,
        'timezone' => 'Africa/Cairo',
        ]);
});

// Activate Or Deactivate Otp
Route::post('otp-activation', [AuthController::class, 'otpActivate']);
// Register
Route::post('register', [AuthController::class, 'register']);
Route::post('loginNew', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'loginLegacy']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('otp', [AuthController::class, 'otp']);
Route::post('check-otp', [AuthController::class, 'checkOtp']);
Route::post('google-login', [AuthController::class, 'googleLogin']);
Route::post('getuser', [AuthController::class, 'authUser']);
Route::post('checkemail', [AuthController::class, 'checkEmail']);
Route::post('checkphone', [AuthController::class, 'checkPhone']);
Route::post('devices', [BajieController::class, 'getDevices']);
Route::post('shops', [BajieController::class, 'getShops']);
Route::get ('shops/new', [ShopsController::class, 'index']);
Route::post('price', [PriceController::class, 'getPriceDescription']);
// Register push token
Route::post('push-token/upsert', [PushTokensController::class, 'upsertToken']);
// Paymob callbacks
Route::post('pay/iframe-callback', [PaymobController::class, 'iframecallback']);
Route::post('pay/moto-callback', [PaymobController::class, 'motoCallback']);

Route::group(['middleware' => 'api', 'prefix' => 'operations'], function () {
    Route::post('rent', [BajieController::class, 'rentPowerbank']);
    Route::post('test', [BajieController::class, 'test']);
    Route::post('update-rent', [BajieController::class, 'updateRentData']);
    Route::post('update-rent-manual', [BajieController::class, 'updateRentManually']);
    // checkReturn
    Route::post('check-return/{id}', [BajieController::class, 'checkReturn']);
    Route::post('add-ticket', [HelpController::class, 'addTicket']);
    Route::post('tickets', [HelpController::class, 'getTickets']);
    Route::post('ticket/{id}', [HelpController::class, 'getTicket']);
    Route::post('add-message', [HelpController::class, 'addMessage']);
    Route::post('orders', [UserController::class, 'getOrders']);
    Route::post('order/{id}', [UserController::class, 'getOrder']);
    Route::post('orderbytrade', [UserController::class, 'getOrderByTradeNo']);
    
    /** Payments Routues */
    Route::post('add-card', [UserController::class, 'addCard']);
    Route::post('remove-card', [UserController::class, 'removeCard']);
    Route::get('get-cards', [UserController::class, 'getCards']);
    Route::post('update-user', [UserController::class, 'updateUser']);
    // Get payment iframe url
    Route::post('get-iframe-url', [PaymentController::class, 'getIframeUrl']);
    Route::post('payment-complete', [PaymobController::class, 'checkPaymentCompletion']);
    // Fawry notifcation url
    Route::post('fawry-notifications', [FawryPayController::class, 'fawryNotification']);
    // Vouchers
    Route::get('vouchers', [ApiVoucherController::class, 'index']);
    // Gifts
    Route::post('gifts', [GiftController::class, 'index']);
        
    // Shops Route Group
    Route::group(['prefix' => 'shops'], function () {
        // Save shops
        Route::post('save', [ShopsController::class, 'save']);
        Route::post('check-save', [ShopsController::class, 'checkSave']);
        Route::get('get-save', [ShopsController::class, 'getSavingShops']);

        // Add comment and react
        Route::post('add-comment', [ShopsController::class, 'addComment']);
        Route::post('add-react', [ShopsController::class, 'addReact']);
    });
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('authUser', [AuthController::class, 'get_user']);
});