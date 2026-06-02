<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Middleware\CheckApprovedUser;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman tunggu untuk user Guest
    Route::get('/waiting-room', [WaitingRoomController::class, 'index'])->name('waiting.room');

    Route::middleware([CheckApprovedUser::class])->group(function () {
        // Route yang bisa diakses Admin & Pustakawan
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('members', MemberController::class);
        Route::post('borrowings/{id}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
        Route::resource('borrowings', BorrowingController::class);

        // Route KHUSUS Admin
        Route::middleware(['role:Admin'])->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});

require __DIR__.'/settings.php';

