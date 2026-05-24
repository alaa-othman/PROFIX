<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
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

    // Vehicle Routes (Web - returns views)
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
    // Main CRUD routes for web
    Route::get('/', [VehicleController::class, 'index'])->name('index');
    Route::get('/create', [VehicleController::class, 'createForm'])->name('create');
    Route::post('/', [VehicleController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [VehicleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [VehicleController::class, 'update'])->name('update');
    Route::delete('/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [VehicleController::class, 'show'])->name('show');
    
    // Additional web routes
    Route::get('/type/{type}', [VehicleController::class, 'showByType'])->name('by-type');
    Route::get('/owner/{userId}', [VehicleController::class, 'showByOwner'])->name('by-owner');
});

    // Order routes
    //Route::resource('orders', OrderController::class);

    // Custom order routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
});

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
