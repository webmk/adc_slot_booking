<?php

use App\Http\Controllers\Admin\AdcCentreController;
use App\Http\Controllers\Admin\AdcDateController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\EmployeeLocationMappingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\CpfLocationMappingController;
use App\Http\Controllers\Admin\LevelManagementController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('employee.index');
    }
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('adc-centres', AdcCentreController::class, ['as' => 'admin']);
    Route::resource('adc-dates', AdcDateController::class, [
        'as' => 'admin'
    ]);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/create', [AdminBookingController::class, 'create'])->name('admin.bookings.create');
    Route::post('/bookings/store', [AdminBookingController::class, 'store'])->name('admin.bookings.store');
    Route::get('/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::resource(
        'location-mappings',
        EmployeeLocationMappingController::class,
        ['as' => 'admin']
    );
    Route::resource(
        'cpf-mappings',
        CpfLocationMappingController::class,
        ['as' => 'admin']
    );

    Route::get('/reports/data', [ReportController::class, 'fetchData'])
        ->name('admin.reports.data');
    Route::get('/reports', [ReportController::class, 'dynamicReport'])
        ->name('admin.reports');
    Route::get('/levels/freeze', [LevelManagementController::class, 'index'])
        ->name('admin.levels.freeze');
    Route::post('/levels/freeze', [LevelManagementController::class, 'update'])
        ->name('admin.levels.freeze.update');
    Route::get('/centres/dates/{centre}', [AdminBookingController::class, 'getDatesForCentre']);
    Route::get('/employees/search', [AdminBookingController::class, 'searchEmployee'])
    ->name('admin.employees.search');
});

//Employee
Route::middleware('auth')->group(function () {
    Route::get('/employee', [BookingController::class, 'employeeInfo'])->name('employee.index');
    // Route::get('/bookings', [BookingController::class, 'index'])->name('employee.bookings.index');
    Route::post('/bookings/preview', [BookingController::class, 'preview'])->name('employee.bookings.preview');
    Route::post('/bookings', [BookingController::class, 'store'])->name('employee.bookings.store');
    Route::get('/bookings/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('employee.bookings.confirmation');
});
