<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Customer routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    // Route::post('/vehicles', [VehicleController::class, 'create'])->name('vehicles.create');
    // Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
    // Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    // Route::get('/vehicles/type/{type}', [VehicleController::class, 'getByType'])->name('vehicles.by-type');
    // Route::get('/vehicles/owner/{userId}', [VehicleController::class, 'getByOwner'])->name('vehicles.by-owner');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
