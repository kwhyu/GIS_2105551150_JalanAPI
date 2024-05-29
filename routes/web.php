<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MapController;

use GuzzleHttp\Client;


// Route untuk halaman tambah ruas jalan


// Route::get('/', function () {
//     return view('dashboard.dashboard-main');
// });

Route::get('/', [LocationController::class, 'view']);

Route::get('/add-ruasjalan', [LocationController::class, 'showAddRuasJalan']);

Route::get('/edit-ruasjalan', [LocationController::class, 'getRuasJalanForEdit'])->name('getRuasJalanForEdit');


// Route::get('/add-ruasjalan', [LocationController::class, 'index']);
// Route::get('/add-ruasjalan', [LocationController::class, 'getMasterPerkerasan']);


// Route::get('/edit-ruasjalan', function () {
//     return view('dashboard.edit-ruas');
// });
Route::post('/submit-ruasjalan', [LocationController::class, 'submitRuasJalan'])->name('submitRuasJalan');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/landing-page', function () {
    return view('dashboard.dashboard-main');
});

Route::get('/lala', function () {
    return view('dashboard.dashboard');
});

Route::post('/register-user', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout'])->name('logout');