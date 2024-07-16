<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Routes
|--------------------------------------------------------------------------
|
| Here is where you can register global routes for your application. These
| routes available to both the website and dashboard.
|
*/

Route::post('/tinymce/upload', [\App\Http\Controllers\TinymceController::class, 'store'])->name('tinymce.upload');
Route::get('/translations/{locale}', [\App\Http\Controllers\TranslationsController::class, 'show'])->name('translations.show');
