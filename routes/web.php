<?php

use App\Http\Controllers\AuthController;
use App\Livewire\BookingWizard;
use App\Livewire\Admin\PackageManagement;
use App\Livewire\Admin\BookingManagement;
use App\Livewire\Admin\PaymentManagement;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/booking', BookingWizard::class)->name('booking.wizard');

// Auth Routes - Admin
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// Auth Routes - User
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('login.post');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    Route::get('/my-bookings', function () {
        return view('user.bookings');
    })->name('user.bookings');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/packages', PackageManagement::class)->name('packages');
    Route::get('/bookings', BookingManagement::class)->name('bookings');
    Route::get('/payments', PaymentManagement::class)->name('payments');
});
