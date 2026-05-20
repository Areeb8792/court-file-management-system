<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PublicTrackController;
use Illuminate\Support\Facades\Route;

// Public Anonymous Case Tracking Portal
Route::get('/track', [PublicTrackController::class, 'index'])->name('public.track');
Route::post('/track', [PublicTrackController::class, 'search'])->name('public.track.search');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Core profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Central dashboard redirector
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-based Dashboards
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    });

    Route::middleware(['role:clerk'])->prefix('clerk')->name('clerk.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'clerk'])->name('dashboard');
    });

    Route::middleware(['role:judge'])->prefix('judge')->name('judge.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'judge'])->name('dashboard');
    });

    Route::middleware(['role:lawyer'])->prefix('lawyer')->name('lawyer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'lawyer'])->name('dashboard');
    });

    Route::middleware(['role:public'])->prefix('public')->name('public.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'public'])->name('dashboard');
    });

    // Case Management (CRUD)
    Route::resource('cases', CaseController::class)->except(['destroy']);
    Route::post('/cases/{case}/assign', [CaseController::class, 'assignStaff'])->name('cases.assign');
    Route::post('/cases/{case}/status', [CaseController::class, 'updateStatus'])->name('cases.status');
    Route::post('/cases/{case}/judgment', [CaseController::class, 'addJudgment'])->name('cases.judgment');

    // Hearing Management
    Route::resource('hearings', HearingController::class)->only(['index', 'store']);
    Route::post('/hearings/{hearing}/reschedule', [HearingController::class, 'reschedule'])->name('hearings.reschedule');
    Route::post('/hearings/{hearing}/remarks', [HearingController::class, 'updateRemarks'])->name('hearings.remarks');

    // Document Management
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/{document}/version', [DocumentController::class, 'uploadVersion'])->name('documents.version');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
});

require __DIR__.'/auth.php';
