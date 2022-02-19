<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', fn(): RedirectResponse => redirect()->route('login'));

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/sign-in', [LoginController::class, 'create'])->name('login');
    Route::post('/sign-in', [LoginController::class, 'store']);
});

