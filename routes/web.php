<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    return view('registration');
});

Route::get('/registration', [RegistrationController::class, 'index']);
Route::post('/registration', [RegistrationController::class, 'store']);
Route::get('/registration/{unique_code}', [RegistrationController::class, 'show']);
Route::post('/registration/{unique_code}/regenerate', [RegistrationController::class, 'regenerate']);
Route::post('/registration/{unique_code}/deactivate', [RegistrationController::class, 'deactivate']);
Route::post('/registration/{unique_code}/lucky', [RegistrationController::class, 'lucky']);
Route::get('/registration/{unique_code}/history', [RegistrationController::class, 'history']);
