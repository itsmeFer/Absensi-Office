<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\AttendanceController;

Route::middleware(['auth'])->group(function() {
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
});

Route::middleware(['auth'])->group(function () {
    // Route untuk staff
    Route::middleware(['role:staff'])->group(function () {
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
    });

    // Route untuk manager
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/team-attendance', [ManagerController::class, 'teamAttendance'])->name('team.attendance');
    });

    // Route untuk admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', Admin\UserController::class);
    });
});
require __DIR__.'/auth.php';
