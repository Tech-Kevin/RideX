<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerRideController;
use App\Http\Controllers\Driver\DriverLocationController;
use App\Http\Controllers\Driver\DriverRideController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    
    Route::get('/login', [AuthController::class, 'showPhoneForm'])->name('login');
    Route::get('/auth/phone', [AuthController::class, 'showPhoneForm'])->name('auth.phone.form');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send.otp');
    Route::get('/verify-otp', [AuthController::class, 'showVerifyForm'])->name('auth.verify.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify.otp');
});

// Email Verification Route (Accessible by guests and auth)
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])->name('verification.verify');

Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes (Require Verified Email)    
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

        // Admin Routes
        Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
            Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\AdminController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::get('/rides', [\App\Http\Controllers\Admin\AdminController::class, 'rides'])->name('rides');
            Route::get('/rates', [\App\Http\Controllers\Admin\AdminController::class, 'vehicleRates'])->name('rates');
            Route::patch('/rates/{rate}', [\App\Http\Controllers\Admin\AdminController::class, 'updateVehicleRate'])->name('rates.update');

            // Verification Routes
            Route::get('/verifications', [\App\Http\Controllers\Admin\AdminController::class, 'verifications'])->name('verifications');
            Route::post('/users/{user}/verify', [\App\Http\Controllers\Admin\AdminController::class, 'verifyUser'])->name('users.verify');

            // Operations Panel
            Route::get('/operations', [\App\Http\Controllers\Admin\AdminController::class, 'operations'])->name('operations');
            Route::get('/operations/metrics', [\App\Http\Controllers\Admin\AdminController::class, 'operationsMetrics'])->name('operations.metrics');

            // Surge Rules (Micro Settings)
            Route::get('/surge-rules', [\App\Http\Controllers\Admin\SurgeRuleController::class, 'index'])->name('surge-rules.index');
            Route::post('/surge-rules', [\App\Http\Controllers\Admin\SurgeRuleController::class, 'store'])->name('surge-rules.store');
            Route::put('/surge-rules/{surgeRule}', [\App\Http\Controllers\Admin\SurgeRuleController::class, 'update'])->name('surge-rules.update');
            Route::post('/surge-rules/{surgeRule}/toggle', [\App\Http\Controllers\Admin\SurgeRuleController::class, 'toggleActive'])->name('surge-rules.toggle');
            Route::delete('/surge-rules/{surgeRule}', [\App\Http\Controllers\Admin\SurgeRuleController::class, 'destroy'])->name('surge-rules.destroy');
        });

        Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/rides', [CustomerRideController::class, 'index'])->name('rides.index');
        Route::get('/rides/create', [CustomerRideController::class, 'create'])->name('rides.create');
        Route::post('/rides', [CustomerRideController::class, 'store'])->name('rides.store');
        Route::get('/rides/nearby-drivers', [CustomerRideController::class, 'nearbyDrivers'])->name('rides.nearby-drivers');
        Route::get('/rides/{ride}', [CustomerRideController::class, 'show'])->name('rides.show');
        Route::get('/rides/{ride}/driver-location', [CustomerRideController::class, 'driverLocation'])->name('rides.driver-location');
        Route::get('/rides/{ride}/status', [CustomerRideController::class, 'rideStatus'])->name('rides.status');
        Route::post('/rides/{ride}/cancel', [CustomerRideController::class, 'cancel'])->name('rides.cancel');
    });

        Route::prefix('driver')->name('driver.')->group(function () {
            Route::get('/rides/available', [DriverRideController::class, 'available'])->name('rides.available');
            Route::get('/rides/poll-available', [DriverRideController::class, 'pollAvailable'])->name('rides.poll-available');
            Route::get('/rides/my', [DriverRideController::class, 'myRides'])->name('rides.my');
            Route::get('/rides/{ride}', [DriverRideController::class, 'show'])->name('rides.show');
            Route::post('/rides/{ride}/accept', [DriverRideController::class, 'accept'])->name('rides.accept');
            Route::post('/rides/{ride}/accept-ajax', [DriverRideController::class, 'acceptAjax'])->name('rides.accept-ajax');
            Route::post('/rides/{ride}/status', [DriverRideController::class, 'updateStatus'])->name('rides.updateStatus');
            Route::post('/rides/{ride}/status-ajax', [DriverRideController::class, 'updateStatusAjax'])->name('rides.updateStatus-ajax');

            Route::post('/location/update', [DriverLocationController::class, 'update'])->name('location.update');
        });

        // Universal Profile Routes
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });
});