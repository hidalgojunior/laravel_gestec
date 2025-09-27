<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateTemplateController;
use App\Http\Controllers\CertificateValidationController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventEnrollmentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/sobre', [PublicController::class, 'about'])->name('public.about');

// Certificate Validation Routes (Public)
Route::prefix('certificate-validation')->group(function () {
    Route::get('/', [CertificateValidationController::class, 'index'])->name('certificate-validation.index');
    Route::get('/{code}', [CertificateValidationController::class, 'validate'])->name('certificate-validation.validate');
    Route::post('/search', [CertificateValidationController::class, 'search'])->name('certificate-validation.search');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Events Routes
    Route::resource('events', EventController::class);

    // Event Enrollments Routes
    Route::prefix('events/{event}')->group(function () {
        Route::post('/enroll', [EventEnrollmentController::class, 'enroll'])->name('event-enrollments.enroll');
        Route::delete('/enroll/{userId}/cancel', [EventEnrollmentController::class, 'cancel'])->name('event-enrollments.cancel');
        Route::get('/enrollments', [EventEnrollmentController::class, 'index'])->name('event-enrollments.index');
    });

    // Activities Routes
    Route::resource('activities', ActivityController::class);

    // Enrollment Routes
    Route::prefix('enrollments')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my-enrollments');
        Route::post('/apply-disqualification-rule', [EnrollmentController::class, 'applyDisqualificationRule'])->name('enrollments.apply-disqualification-rule');
    });

    Route::prefix('activities/{activity}')->group(function () {
        Route::post('/enroll', [EnrollmentController::class, 'enroll'])->name('enrollments.enroll');
    });

    Route::prefix('enrollments/{enrollment}')->group(function () {
        Route::delete('/cancel', [EnrollmentController::class, 'cancel'])->name('enrollments.cancel');
        Route::post('/upload-proof', [EnrollmentController::class, 'uploadProof'])->name('enrollments.upload-proof');
        Route::post('/confirm-payment', [EnrollmentController::class, 'confirmPayment'])->name('enrollments.confirm-payment');
    });

    // Presence Routes
    Route::prefix('presences')->group(function () {
        Route::get('/', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/validate', [PresenceController::class, 'validatePresence'])->name('presences.validate');
    });

    Route::prefix('activities/{activity}/presences')->group(function () {
        Route::get('/report', [PresenceController::class, 'report'])->name('presences.report');
    });

    Route::prefix('presences/{presence}')->group(function () {
        Route::delete('/remove', [PresenceController::class, 'removePresence'])->name('presences.remove');
    });

    // Certificate Routes
    Route::prefix('certificates')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/generate', [CertificateController::class, 'generate'])->name('certificates.generate');
        Route::get('/admin', [CertificateController::class, 'admin'])->name('certificates.admin');
        Route::get('/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
        Route::delete('/{certificate}/revoke', [CertificateController::class, 'revoke'])->name('certificates.revoke');
    });

    // Certificate Template Routes
    Route::resource('certificate-templates', CertificateTemplateController::class);

    // Password Change Routes
    Route::prefix('password')->group(function () {
        Route::get('/change', [PasswordChangeController::class, 'show'])->name('password.change');
        Route::post('/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
    });

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class, ['as' => 'admin']);

        // Settings
        Route::prefix('settings')->group(function () {
            Route::get('/', [AdminController::class, 'settings'])->name('admin.settings');
            Route::post('/', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        });

        // Backup
        Route::get('/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');
        Route::post('/restore', [AdminController::class, 'restoreDatabase'])->name('admin.restore');
    });
});

require __DIR__.'/auth.php';
