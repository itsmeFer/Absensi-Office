<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // Redirect to login page
});

// Auth routes (guest-only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Protected routes (auth-only)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Staff routes
    Route::middleware(['role:staff'])->group(function () {
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
    });

    // Manager routes
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/team-attendance', [ManagerController::class, 'teamAttendance'])->name('team.attendance');
    });

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Attendance management
        Route::prefix('attendance')->group(function () {
            Route::put('/{attendance}/update', [DashboardController::class, 'updateAttendanceStatus'])->name('admin.attendance.update');
            Route::delete('/{attendance}/delete', [DashboardController::class, 'deleteAttendance'])->name('admin.attendance.delete');
            Route::get('/previous', [AttendanceController::class, 'showPreviousAttendance'])->name('admin.attendance.previous');
        });
    });

    
});

require __DIR__ . '/auth.php';
