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
Livewire::setScriptRoute(function ($handle) {
    return Route::get('/vendor/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/vendor/livewire/update', $handle);
});
Route::post('/tinymce/upload', [\App\Http\Controllers\TinymceController::class, 'store'])->name('tinymce.upload');
Route::get('/translations/{locale}', [\App\Http\Controllers\TranslationsController::class, 'show'])->name('translations.show');
