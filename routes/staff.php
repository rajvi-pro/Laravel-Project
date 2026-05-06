<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffAuthController;

// Staff Authentication Routes
Route::prefix('staff')->group(function () {
    Route::get('/login', [StaffAuthController::class, 'showLogin'])->name('staff.login');
    Route::post('/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');
});

// Protected Staff Routes
Route::prefix('staff')->middleware('staff')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    // Profile & Password
    Route::get('/profile', [StaffDashboardController::class, 'profile'])->name('staff.profile');
    Route::get('/change-password', [StaffDashboardController::class, 'showChangePasswordForm'])->name('staff.change-password');
    Route::post('/change-password', [StaffDashboardController::class, 'changePassword'])->name('staff.change-password.update');

    // Appointments Management
    Route::get('/appointments', [StaffDashboardController::class, 'appointments'])->name('staff.appointments');
    Route::post('/appointments', [StaffDashboardController::class, 'storeAppointment'])->name('staff.appointment.store');
    Route::get('/appointments/{id}', [StaffDashboardController::class, 'appointmentDetail'])->name('staff.appointment.detail');
    Route::put('/appointments/{id}', [StaffDashboardController::class, 'updateAppointment'])->name('staff.appointment.update');
    Route::post('/appointments/{id}/reschedule', [StaffDashboardController::class, 'rescheduleAppointment'])->name('staff.appointment.reschedule');
    Route::post('/appointments/available-slots', [StaffDashboardController::class, 'getAvailableTimeSlots'])->name('staff.appointment.available-slots');
    Route::patch('/appointments/{id}/cancel', [StaffDashboardController::class, 'cancelAppointment'])->name('staff.appointment.cancel');
    Route::delete('/appointments/{id}', [StaffDashboardController::class, 'deleteAppointment'])->name('staff.appointment.delete');

    // Patient Management
    Route::get('/patients', [StaffDashboardController::class, 'patients'])->name('staff.patients');
    Route::get('/patients/{id}', [StaffDashboardController::class, 'patientDetail'])->name('staff.patient.detail');
    Route::get('/patients/{id}/edit', [StaffDashboardController::class, 'editPatient'])->name('staff.patient.edit');
    Route::put('/patients/{id}', [StaffDashboardController::class, 'updatePatient'])->name('staff.patient.update');
    Route::post('/patients/{id}/history', [StaffDashboardController::class, 'storePatientHistory'])->name('staff.patient.history.store');

    // Doctor Management
    Route::get('/doctors', [StaffDashboardController::class, 'doctors'])->name('staff.doctors');
    Route::get('/doctors/{id}/schedule', [StaffDashboardController::class, 'doctorSchedule'])->name('staff.doctor.schedule');

    // Billing Management
    Route::get('/billings', [StaffDashboardController::class, 'billings'])->name('staff.billings');
    Route::get('/billings/create', [StaffDashboardController::class, 'createBilling'])->name('staff.billing.create');
    Route::post('/billings', [StaffDashboardController::class, 'storeBilling'])->name('staff.billing.store');
    Route::get('/billings/{id}', [StaffDashboardController::class, 'billingDetail'])->name('staff.billing.detail');
    Route::put('/billings/{id}', [StaffDashboardController::class, 'updateBilling'])->name('staff.billing.update');
    Route::delete('/billings/{id}', [StaffDashboardController::class, 'deleteBilling'])->name('staff.billing.delete');
    Route::post('/billings/{id}/send-email', [StaffDashboardController::class, 'sendBillingEmail'])->name('staff.billing.send-email');
    
    // Billing AJAX Endpoints
    Route::get('/billing/appointment/{appointment}/details', [StaffDashboardController::class, 'getAppointmentDetails'])->name('staff.billing.appointment-details');
    Route::post('/billing/create-from-appointment', [StaffDashboardController::class, 'createBillingFromAppointment'])->name('staff.billing.create-from-appointment');

    // Medicines Management (optional)
    Route::get('/medicines', [StaffDashboardController::class, 'medicines'])->name('staff.medicines');
    Route::get('/medicines/create', [StaffDashboardController::class, 'createMedicine'])->name('staff.medicine.create');
    Route::post('/medicines', [StaffDashboardController::class, 'storeMedicine'])->name('staff.medicine.store');

    // Lab Results Management (optional)
    Route::get('/lab-results', [StaffDashboardController::class, 'labResults'])->name('staff.lab-results');
    
    // Doctor Unavailability Management
    Route::get('/unavailable-doctors', [StaffDashboardController::class, 'unavailableDoctors'])->name('staff.unavailable-doctors');
    Route::get('/affected-appointments/{unavailabilityId}', [StaffDashboardController::class, 'affectedAppointments'])->name('staff.affected-appointments');
    
    // Search
    Route::get('/search', [StaffDashboardController::class, 'search'])->name('staff.search');
    Route::get('/search/suggestions', [StaffDashboardController::class, 'searchSuggestions'])->name('staff.search.suggestions');
});

