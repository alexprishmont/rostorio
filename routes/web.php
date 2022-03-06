<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Users\Employees\EmployeesController;
use App\Http\Controllers\Users\Settings\SettingsController;
use App\Http\Controllers\Users\Setup\CompanySetupController;
use App\Models\User;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): RedirectResponse => redirect()->route('login'));

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/sign-in', [LoginController::class, 'create'])->name('login');
    Route::post('/sign-in', [LoginController::class, 'store']);

    Route::get('/sign-up', [RegisterController::class, 'create'])->name('register');
    Route::post('/sign-up', [RegisterController::class, 'store']);
});

Route::group(['middleware' => ['auth']], function (): void {
    Route::post('/logout', [LogoutController::class, 'store'])->name('logout');
    Route::resource('dashboard', DashboardController::class)->only('index')
        ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
            ->index('Dashboard'));

    Route::resource('employees', EmployeesController::class)
        ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
            ->index('Employees')
            ->create('Add new employee')
            ->show(fn (User $user): string => sprintf('%s %s', $user->firstname, $user->lastname))
            ->edit('Edit'));

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::resource('settings', SettingsController::class)->only('index');
    });

    Route::group(['prefix' => 'company', 'as' => 'company.'], function () {
        Route::resource(
            'initialSetup',
            CompanySetupController::class
        )->only('store');
    });
});
