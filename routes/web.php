<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): RedirectResponse => redirect()->route('login'));

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/sign-in', [LoginController::class, 'create'])->name('login');
    Route::post('/sign-in', [LoginController::class, 'store']);

    Route::get('/sign-up', [RegisterController::class, 'create'])->name('register');
    Route::post('/sign-up', [RegisterController::class, 'store']);
});

Route::group(['middleware' => 'auth'], function (): void {
    Route::resource('dashboard', DashboardController::class)->only('index');
});
