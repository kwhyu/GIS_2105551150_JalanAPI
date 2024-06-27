<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\MapController;

use GuzzleHttp\Client;


// Route untuk halaman tambah ruas jalan

Route::get('/edit-ruasjalan', [CrudController::class, 'index']);
Route::get('/get-kabupaten', [CrudController::class, 'getKabupaten']);
Route::get('/get-kecamatan', [CrudController::class, 'getKecamatan']);
Route::get('/get-desa', [CrudController::class, 'getDesa']);

// Route::get('/get-provinsi', [LocationController::class, 'getProvinsi'])->name('getProvinsi');
// Route::get('/get-kabupaten/{provinsiId}', [LocationController::class, 'getKabupatenByProvinsi'])->name('getKabupatenByProvinsi');
// Route::get('/get-kecamatan/{kabupatenId}', [LocationController::class, 'getKecamatanByKabupaten'])->name('getKecamatanByKabupaten');
// Route::get('/get-desa/{kecamatanId}', [LocationController::class, 'getDesaByKecamatan'])->name('getDesaByKecamatan');

// Route::get('kabupaten/{id_prov}', [LocationController::class, 'getKabupatenByProvinsi']);
// Route::get('kecamatan/{id_kec}', [LocationController::class, 'getKecamatanByKabupaten']);
// Route::get('/regions/desa/{kecamatan_id}', [LocationController::class, 'getDesaByKecamatan']);

// Route::get('/', function () {
//     return view('dashboard.dashboard-main');
// });

Route::get('/', [LocationController::class, 'view']);

Route::get('/add-ruasjalan', [LocationController::class, 'showAddRuasJalan']);

Route::get('/edit-ruasjalan', [LocationController::class, 'getRuasJalanForEdit'])->name('getRuasJalanForEdit');


Route::put('/ruasjalan/update/{id}', [LocationController::class, 'updateRuasJalan'])->name('ruasjalan.update');

Route::delete('/ruasjalan/delete/{id}', [LocationController::class, 'deleteRuasJalan'])->name('ruasjalan.delete');;


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
