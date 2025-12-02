<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MaintenanceTemplateController;
use App\Http\Controllers\CheckSheetTemplateController;

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Assets
    Route::resource('assets', AssetController::class);
    
    // Karyawan
    Route::resource('karyawan', KaryawanController::class);
    
    // Maintenance Template
    Route::resource('maintenance-template', MaintenanceTemplateController::class);
    
    // Check Sheet Template
    Route::get('check-sheet-template/komponen/{assetId}', [CheckSheetTemplateController::class, 'getKomponenByAsset'])->name('check-sheet-template.get-komponen');
    Route::resource('check-sheet-template', CheckSheetTemplateController::class);
});
