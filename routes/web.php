<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\IpPoolController;
use App\Http\Controllers\OdcController;
use App\Http\Controllers\OdpController;
use App\Http\Controllers\PelangganController;


Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('ip-pool', IpPoolController::class)->except(['show', 'edit', 'update']);
    Route::resource('odc', OdcController::class)->except(['show']);
    Route::resource('odp', OdpController::class)->except(['show']);
    Route::resource('pelanggan', PelangganController::class);
    Route::get('get-pools', [PelangganController::class, 'getPools'])->name('get.pools');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Paket
    Route::resource('paket', PaketController::class)->except(['show']);
});