<?php

use App\Http\Controllers\Admin\AdcCentreController;
use App\Http\Controllers\Admin\AdcDateController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function() { return redirect()->route('login'); });

// Auth
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Admin
Route::middleware(['auth','is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('adc-centres', AdcCentreController::class, ['as'=>'admin']);
    Route::resource('adc-dates', AdcDateController::class, [
        'as' => 'admin'
    ]);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
});

//Employee
Route::middleware('auth')->group(function () {
    Route::get('/employee', [BookingController::class, 'employeeInfo'])->name('employee.index');
    // Route::get('/bookings', [BookingController::class, 'index'])->name('employee.bookings.index');
    Route::post('/bookings/preview', [BookingController::class, 'preview'])->name('employee.bookings.preview');
    Route::post('/bookings', [BookingController::class, 'store'])->name('employee.bookings.store');
    Route::get('/bookings/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('employee.bookings.confirmation');
});
