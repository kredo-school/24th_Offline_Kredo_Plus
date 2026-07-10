<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarenderiaController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\RestaurantCafeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// information
Route::prefix('information')->group(function () {
    Route::get('/carenderia', [CarenderiaController::class, 'index'])->name('carenderia.index');
    Route::get('/restaurant-cafe', [RestaurantCafeController::class, 'index'])->name('restaurant-cafe.index');
    Route::get('/travel', [TravelController::class, 'index'])->name('travel.index');
    Route::get('/travel/{slug}', [TravelController::class, 'show'])->name('travel.show');
    Route::get('/other', [OtherController::class, 'index'])->name('other.index');
});

require __DIR__.'/auth.php';
