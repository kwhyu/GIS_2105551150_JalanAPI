<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MapController;
use GuzzleHttp\Client;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
});


Route::post('/register-user', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/ruasjalan', [MapController::class, 'showpoly'])->name('dashboard.dashboard');


