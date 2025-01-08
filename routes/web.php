<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;  
use App\Http\Controllers\Auth\LoginController;  // Pastikan untuk mengimpor LoginController jika perlu

Route::get('/', function () {
    return redirect()->route('login'); // Arahkan ke halaman login
});

// Auth routes (pastikan login dan registrasi sudah ada)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Semua route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Route lainnya setelah login
});


// Group untuk semua route yang memerlukan authentication
Route::middleware(['auth'])->group(function () {
    // Route dashboard dengan redirect berdasarkan role
    Route::get('/dashboard', function () {
        if(auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Route untuk mengubah status absensi
    Route::put('/attendance/{attendance}/update', [DashboardController::class, 'updateAttendanceStatus'])->name('admin.attendance.update');
    
    // Route untuk menghapus absensi
    Route::delete('/attendance/{attendance}/delete', [DashboardController::class, 'deleteAttendance'])->name('admin.attendance.delete');
});

});

require __DIR__.'/auth.php';
