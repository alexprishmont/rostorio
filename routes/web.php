<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Companies\CompanyController;
use App\Http\Controllers\Companies\Employees\EmployeesController;
use App\Http\Controllers\Companies\RolesController;
use App\Http\Controllers\Companies\Setup\CompanySetupController;
use App\Http\Controllers\Shifts\AvailableShiftRequestsTypesController;
use App\Http\Controllers\Shifts\RequestsController;
use App\Http\Controllers\Shifts\ShiftsController;
use App\Http\Controllers\Users\Settings\SettingsController;
use App\Http\Controllers\Users\ShiftRequestsController;
use App\Http\Controllers\Users\WorkController;
use App\Models\Company;
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

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::resource('settings', SettingsController::class)->only('index');

        Route::group(['prefix' => 'work', 'as' => 'work.'], function (): void {
            Route::resource('requests', ShiftRequestsController::class)
                ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
                    ->index('Pamainų pageidavimai')
                    ->create('Pamainų pageidavimai'));
        });

        Route::resource('work', WorkController::class)
            ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
                ->index('Pradinis'));
    });

    Route::resource('shifts', ShiftsController::class)
        ->only(['index', 'create'])
        ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
            ->index('Darbo grafikas')
            ->create('Darbo grafiko valdymas'));

    Route::group(['prefix' => 'shifts', 'as' => 'shifts.'], function (): void {
        Route::get(
            '/requests/types',
            [AvailableShiftRequestsTypesController::class, 'index']
        )->name('requests.types');
        Route::get(
            '/requests/{user}',
            [RequestsController::class, 'show']
        )->name('requests.show');

        Route::post('/save', [ShiftsController::class, 'save'])->name('save');
        Route::get('/generate/{date}', [ShiftsController::class, 'generate'])->name('generate');
        Route::get('/getByDate/{date}', [ShiftsController::class, 'getByDate']);
    });

    Route::group(['prefix' => 'company', 'as' => 'company.'], function () {
        Route::resource(
            'initialSetup',
            CompanySetupController::class
        )->only(['index', 'store']);

        Route::resource('roles', RolesController::class)
            ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
                ->index('Vartotojų rolės'));

        Route::resource('employees', EmployeesController::class)
            ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
                ->index('Darbuotojai')
                ->create('Pridėti naują darbuotoją')
                ->show(fn (User $user): string => sprintf('%s %s', $user->firstname, $user->lastname))
                ->edit('Redaguoti'));

        Route::group(['prefix' => 'employees', 'as' => 'employees.'], function (): void {
            Route::put('/{employee}/removeRole', [EmployeesController::class, 'removeRole']);
            Route::post('/{employee}/addRole', [EmployeesController::class, 'addRole']);
        });
    });

    Route::resource('company', CompanyController::class)
        ->breadcrumbs(fn (ResourceBreadcrumbs $breadcrumbs): ResourceBreadcrumbs => $breadcrumbs
            ->index('Įmonė')
            ->show(fn (Company $company): string => sprintf('%s', $company->name))
            ->edit('Redaguoti'));
});
