<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateTemplateController;
use App\Http\Controllers\CertificateValidationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Routes
Route::get('/sobre', [PublicController::class, 'about'])->name('public.about');

// Certificate Validation Routes (Public)
Route::get('/certificate-validation', [CertificateValidationController::class, 'index'])->name('certificate-validation.index');
Route::get('/certificate-validation/{code}', [CertificateValidationController::class, 'validate'])->name('certificate-validation.validate');
Route::post('/certificate-validation/search', [CertificateValidationController::class, 'search'])->name('certificate-validation.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events Routes
    Route::resource('events', EventController::class);

    // Activities Routes
    Route::resource('activities', ActivityController::class);

    // Enrollment Routes
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/activities/{activity}/enroll', [EnrollmentController::class, 'enroll'])->name('enrollments.enroll');
    Route::delete('/enrollments/{enrollment}/cancel', [EnrollmentController::class, 'cancel'])->name('enrollments.cancel');
    Route::post('/enrollments/{enrollment}/upload-proof', [EnrollmentController::class, 'uploadProof'])->name('enrollments.upload-proof');
    Route::post('/enrollments/{enrollment}/confirm-payment', [EnrollmentController::class, 'confirmPayment'])->name('enrollments.confirm-payment');
    Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my-enrollments');
    Route::post('/apply-disqualification-rule', [EnrollmentController::class, 'applyDisqualificationRule'])->name('enrollments.apply-disqualification-rule');

    // Presence Routes
    Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::post('/presences/validate', [PresenceController::class, 'validatePresence'])->name('presences.validate');
    Route::get('/activities/{activity}/presences/report', [PresenceController::class, 'report'])->name('presences.report');
    Route::delete('/presences/{presence}/remove', [PresenceController::class, 'removePresence'])->name('presences.remove');

    // Certificate Routes
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/generate', [CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/admin', [CertificateController::class, 'admin'])->name('certificates.admin');
    Route::delete('/certificates/{certificate}/revoke', [CertificateController::class, 'revoke'])->name('certificates.revoke');

    // Certificate Template Routes
    Route::resource('certificate-templates', CertificateTemplateController::class);

    // Password Change Routes
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');

    // User Management Routes (Admin only)
    Route::resource('admin/users', UserController::class, ['as' => 'admin']);
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');

    // Admin Routes
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/admin/maintenance', [AdminController::class, 'maintenance'])->name('admin.maintenance');
    Route::post('/admin/maintenance/toggle', [AdminController::class, 'toggleMaintenance'])->name('admin.maintenance.toggle');
    Route::get('/admin/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');
    Route::post('/admin/restore', [AdminController::class, 'restoreDatabase'])->name('admin.restore');
});

require __DIR__.'/auth.php';
