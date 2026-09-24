<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public home page. Redirects to /dashboard automatically for signed-in
// users (see HomeController). Replaces the previous unconditional
// Route::redirect('/', '/dashboard').
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile — available to every authenticated role, each user edits only their own.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Category management — Admin only.
    Route::resource('categories', CategoryController::class)->except(['show'])->middleware('role:admin');

   // Medicines
// All authenticated roles can view medicines.
// Only Admin and Pharmacist can create, update and delete medicines.

Route::get('/medicines', [MedicineController::class, 'index'])
    ->name('medicines.index');

Route::get('/medicines/create', [MedicineController::class, 'create'])
    ->name('medicines.create')
    ->middleware('role:admin,pharmacist');

Route::post('/medicines', [MedicineController::class, 'store'])
    ->name('medicines.store')
    ->middleware('role:admin,pharmacist');

Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])
    ->name('medicines.show');

Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])
    ->name('medicines.edit')
    ->middleware('role:admin,pharmacist');

Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])
    ->name('medicines.update')
    ->middleware('role:admin,pharmacist');

Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])
    ->name('medicines.destroy')
    ->middleware('role:admin,pharmacist');

    // Batches — Admin & Pharmacist manage batch records.
    Route::prefix('medicines/{medicine}/batches')->name('batches.')->middleware('role:admin,pharmacist')->group(function () {
        Route::get('/create', [BatchController::class, 'create'])->name('create');
        Route::post('/', [BatchController::class, 'store'])->name('store');
        Route::get('/{batch}/edit', [BatchController::class, 'edit'])->name('edit');
        Route::put('/{batch}', [BatchController::class, 'update'])->name('update');
        Route::delete('/{batch}', [BatchController::class, 'destroy'])->name('destroy');
    });

    // Stock movements, history, expiry & out-of-stock views — open to every role
    // (recording stock in/out is a day-to-day Staff task too).
    Route::post('/stock/{batch}/update', [StockController::class, 'update'])->name('stock.update');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');
    Route::get('/out-of-stock', [StockController::class, 'outOfStock'])->name('out-of-stock.index');

    Route::get('/expiry-alerts', [ExpiryController::class, 'index'])->name('expiry-alerts.index');

    // Reports — Admin & Pharmacist.
    Route::prefix('reports')->name('reports.')->middleware('role:admin,pharmacist')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/expiry', [ReportController::class, 'expiry'])->name('expiry');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    // Settings — Admin only.
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // User management — Admin only.
        Route::resource('users', UserController::class)->except(['show']);
    });
});
