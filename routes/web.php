<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FPGController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComposerController;

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Migration completed';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});

Route::get('/bersihkan', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('route:cache');
    return 'DONE';
});

Route::get('/run-composer', function () {
    putenv('HOME=' . __DIR__);
    putenv('COMPOSER_HOME=' . __DIR__ . '/.composer');

    $output = [];
    $return_var = 0;
    // Ganti path berikut dengan path yang benar ke composer di server Anda
    exec('/usr/bin/composer require alxdm/graphviz 2>&1', $output, $return_var);

    return response()->json([
        'output' => $output,
        'return_code' => $return_var,
    ]);
});

Route::get('/', function () {
    return redirect('/dashboard');
});

//-- ADMIN --//
// Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::get('/login', 'login')->name('login');
    Route::post('/store', 'store')->name('store');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/logout', 'logout')->name('logout');
});

// Data
Route::controller(DataController::class)->group(function () {
    Route::get('/data', 'index')->name('data');
    Route::post('/data', 'tambah');
    Route::post('/data/update', 'update');
    Route::get('/data/hapus/{id}', 'hapus');
    Route::delete('/data/hapus-semua', 'hapusSemuaData');
    Route::post('/data/upload-excel', 'uploadExcel');
});

// FPG
Route::controller(FPGController::class)->group(function () {
    Route::get('/fpg', 'index')->name('fpg');
    Route::post('/fpg/proses1', 'proses1');
    Route::post('/fpg/proses2', 'proses2');
    Route::post('/fpg/proses3', 'proses3');
    Route::post('/fpg/proses4', 'proses4');
    Route::post('/fpg/update', 'update');
    Route::get('/fpg/hapus/{id}', 'hapus');
    Route::get('/fpg/hasil', 'hasil');
});

// Hasil
// Route::controller(HasilController::class)->group(function () {
//     Route::get('/hasil', 'index')->name('hasil');
//     Route::post('/hasil', 'tambah');
//     Route::post('/hasil/update', 'update');
//     Route::get('/hasil/hapus/{id}', 'hapus');
// });
Route::get('/hasil', function () {
    return redirect()->back()->with('error', 'Halaman masih dalam tahap pengembangan');
});


// Panduan
Route::controller(PanduanController::class)->group(function () {
    Route::get('/panduan', 'index')->name('panduan');
    Route::post('/panduan', 'tambah');
    Route::post('/panduan/update', 'update');
    Route::get('/panduan/hapus/{id}', 'hapus');
});

// User
Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('user');
    Route::post('/user', 'tambah');
    Route::post('/user/update', 'update');
    Route::post('/user/updatepass', 'updatePass');
    Route::get('/user/hapus/{id}', 'hapus');
    Route::get('/user/profil', 'profil')->name('user.profil');
    Route::post('/user/profil/update', 'updateProfil');
    Route::post('/user/profil/updatepass', 'updateProfilPass');
});

// Log
Route::controller(LogController::class)->group(function () {
    Route::get('/log', 'index')->name('log');
    Route::get('/log/hapus/{id}', 'hapus');
});
