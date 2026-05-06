<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorAuthController;
use App\Http\Controllers\Doctor\DoctorDashboardController;

// Doctor Authentication Routes
Route::prefix('doctor')->group(function () {
    Route::get('/login', [DoctorAuthController::class, 'showLogin'])->name('doctor.login');
    Route::post('/login', [DoctorAuthController::class, 'login'])->name('doctor.login.submit');
    Route::post('/logout', [DoctorAuthController::class, 'logout'])->name('doctor.logout');
});

// Protected Doctor Routes (middleware protection)
Route::prefix('doctor')->middleware('doctor')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
    
    // Profile & Password
    Route::get('/profile', [DoctorDashboardController::class, 'profile'])->name('doctor.profile');
    Route::get('/change-password', [DoctorDashboardController::class, 'showChangePasswordForm'])->name('doctor.change-password');
    Route::post('/change-password', [DoctorDashboardController::class, 'changePassword'])->name('doctor.change-password.update');
    
    Route::post('/availability', [DoctorDashboardController::class, 'updateAvailability'])->name('doctor.availability.update');

    // Unavailability
    Route::get('/unavailable', [DoctorDashboardController::class, 'unavailabilityForm'])->name('doctor.unavailable.form');
    Route::post('/unavailable', [DoctorDashboardController::class, 'markUnavailable'])->name('doctor.unavailable.store');
    Route::delete('/unavailable/{unavailabilityId}', [DoctorDashboardController::class, 'removeUnavailability'])->name('doctor.unavailable.delete');

    // Schedule Management
    Route::get('/schedule', [DoctorDashboardController::class, 'scheduleList'])->name('doctor.schedule-list');
    Route::get('/schedule/create', [DoctorDashboardController::class, 'createSchedule'])->name('doctor.schedule-create');
    Route::post('/schedule', [DoctorDashboardController::class, 'storeSchedule'])->name('doctor.schedule-store');
    Route::get('/schedule/{id}/edit', [DoctorDashboardController::class, 'editSchedule'])->name('doctor.schedule-edit');
    Route::put('/schedule/{id}', [DoctorDashboardController::class, 'updateSchedule'])->name('doctor.schedule-update');
    Route::delete('/schedule/{id}', [DoctorDashboardController::class, 'deleteSchedule'])->name('doctor.schedule-delete');
    Route::patch('/schedule/{id}/toggle', [DoctorDashboardController::class, 'toggleScheduleStatus'])->name('doctor.schedule-toggle');


    // Appointments
    Route::get('/appointments', [DoctorDashboardController::class, 'appointments'])->name('doctor.appointments');
    Route::get('/appointments/{appointmentId}', [DoctorDashboardController::class, 'appointmentDetail'])->name('doctor.appointment.detail');

    // Patients
    Route::get('/patients', [DoctorDashboardController::class, 'patients'])->name('doctor.patients');
    Route::get('/patients/{id}', [DoctorDashboardController::class, 'patientDetails'])->name('doctor.patient.details');

    // Prescriptions
    Route::get('/prescriptions', [DoctorDashboardController::class, 'prescriptions'])->name('doctor.prescriptions');
    Route::get('/prescriptions/create', [DoctorDashboardController::class, 'showCreatePrescriptionForm'])->name('doctor.prescription.create.form');
    Route::post('/prescriptions', [DoctorDashboardController::class, 'createPrescription'])->name('doctor.prescription.create');
    Route::get('/prescriptions/{prescriptionId}', [DoctorDashboardController::class, 'prescriptionDetail'])->name('doctor.prescription.detail');

    // Medical Reports
    Route::get('/medical-reports', [DoctorDashboardController::class, 'medicalReports'])->name('doctor.medical-reports');
    Route::get('/medical-reports/create', [DoctorDashboardController::class, 'showCreateMedicalReportForm'])->name('doctor.medical-report.create.form');
    Route::get('/medical-reports/simple', [DoctorDashboardController::class, 'medicalReportsSimple'])->name('doctor.medical-reports-simple');
    Route::post('/medical-reports', [DoctorDashboardController::class, 'createMedicalReport'])->name('doctor.medical-report.create');

    // Lab Results
    Route::get('/lab-results', [DoctorDashboardController::class, 'labResults'])->name('doctor.lab-results');
    Route::get('/lab-results/create', [DoctorDashboardController::class, 'showCreateLabResultForm'])->name('doctor.lab-result.create.form');
    Route::get('/lab-results/simple', [DoctorDashboardController::class, 'labResultsSimple'])->name('doctor.lab-results-simple');
    Route::post('/lab-results', [DoctorDashboardController::class, 'createLabResult'])->name('doctor.lab-result.create');

    // Search
    Route::get('/search', [DoctorDashboardController::class, 'search'])->name('doctor.search');
    Route::get('/search/suggestions', [DoctorDashboardController::class, 'searchSuggestions'])->name('doctor.search.suggestions');
});
