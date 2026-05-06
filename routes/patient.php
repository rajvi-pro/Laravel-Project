<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\PatientAuthController;
use App\Http\Controllers\Patient\PatientDashboardController;

// Patient Authentication Routes (No Middleware)
Route::prefix('patient')->group(function () {
    Route::get('/login', [PatientAuthController::class, 'showLogin'])->name('patient.login');
    Route::post('/login', [PatientAuthController::class, 'login'])->name('patient.login.submit');
    Route::get('/register', [PatientAuthController::class, 'showRegister'])->name('patient.register');
    Route::post('/register', [PatientAuthController::class, 'register'])->name('patient.register.submit');
    Route::post('/logout', [PatientAuthController::class, 'logout'])->name('patient.logout');
    
    // Forgot password flow (no login required)
    Route::get('/forgot-password', [PatientAuthController::class, 'showForgotPasswordForm'])->name('patient.forgot-password');
    Route::post('/forgot-password', [PatientAuthController::class, 'resetPassword'])->name('patient.forgot-password.update');
});

// Protected Patient Routes (middleware protection)
Route::prefix('patient')->middleware('patient')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');

    // Profile
    Route::get('/profile', [PatientDashboardController::class, 'profile'])->name('patient.profile');
    Route::get('/profile/edit', [PatientDashboardController::class, 'editProfile'])->name('patient.profile.edit');
    Route::get('/profile/edit-view', [PatientDashboardController::class, 'editProfileView'])->name('patient.profile.edit-view');
    Route::put('/profile', [PatientDashboardController::class, 'updateProfile'])->name('patient.profile.update');
    Route::get('/change-password', [PatientDashboardController::class, 'showChangePasswordForm'])->name('patient.change-password');
    Route::post('/change-password', [PatientDashboardController::class, 'changePassword'])->name('patient.change-password.update');
    Route::get('/add-member', [PatientDashboardController::class, 'showAddMemberForm'])->name('patient.add-member');
    Route::post('/add-member', [PatientDashboardController::class, 'addMember'])->name('patient.add-member.store');

    Route::get('/doctors/search', [PatientDashboardController::class, 'doctorSearch'])->name('patient.doctor-search');
    Route::get('/doctors/suggestions', [PatientDashboardController::class, 'doctorSuggestions'])->name('patient.doctor-suggestions');
    Route::get('/doctors/select', [PatientDashboardController::class, 'selectDoctor'])->name('patient.doctor-select');
    Route::get('/doctors/{doctorId}/quick-book', [PatientDashboardController::class, 'quickBookAppointment'])->name('patient.quick-book');

    // Appointments
    Route::get('/appointments', [PatientDashboardController::class, 'appointments'])->name('patient.appointments');
    Route::get('/appointments/create', [PatientDashboardController::class, 'createAppointmentForm'])->name('patient.appointment.create');
    Route::post('/appointments', [PatientDashboardController::class, 'storeAppointment'])->name('patient.appointment.store');
    Route::get('/appointments/{appointmentId}', [PatientDashboardController::class, 'appointmentDetail'])->name('patient.appointment.detail');
    Route::get('/appointments/{appointmentId}/cancel', [PatientDashboardController::class, 'showCancelAppointmentForm'])->name('patient.appointment.cancel.form');
    Route::post('/appointments/{appointmentId}/cancel', [PatientDashboardController::class, 'cancelAppointment'])->name('patient.appointment.cancel');

    // Manual Reschedule
    Route::get('/appointments/{appointmentId}/manual-reschedule', [PatientDashboardController::class, 'manualRescheduleForm'])->name('patient.appointment.manualReschedule');
    Route::post('/appointments/{appointmentId}/manual-reschedule', [PatientDashboardController::class, 'manualRescheduleSave'])->name('patient.appointment.manualRescheduleSave');
    
    // Check Doctor Availability (AJAX)
    Route::post('/check-availability', [PatientDashboardController::class, 'checkAvailability'])->name('patient.checkAvailability');
    
    // Get Available Time Slots (AJAX)
    Route::post('/get-available-slots', [PatientDashboardController::class, 'getAvailableTimeSlots'])->name('patient.getAvailableSlots');

    // Medical History
    Route::get('/medical-history', [PatientDashboardController::class, 'medicalHistory'])->name('patient.medical-history');
    Route::get('/medical-history/{reportId}', [PatientDashboardController::class, 'medicalReportDetail'])->name('patient.medical-report.detail');

    // Prescriptions
    Route::get('/prescriptions', [PatientDashboardController::class, 'prescriptions'])->name('patient.prescriptions');
    Route::get('/prescriptions/{prescriptionId}', [PatientDashboardController::class, 'prescriptionDetail'])->name('patient.prescription.detail');

    // Lab Results
    Route::get('/lab-results', [PatientDashboardController::class, 'labResults'])->name('patient.lab-results');
    Route::get('/lab-results/{labResultId}', [PatientDashboardController::class, 'labResultDetail'])->name('patient.lab-result.detail');

    // Billing
    Route::get('/billings', [PatientDashboardController::class, 'billings'])->name('patient.billings');
    Route::get('/billings/{billingId}', [PatientDashboardController::class, 'billingDetail'])->name('patient.billing.view');
    Route::post('/billings/{billingId}/mark-paid', [PatientDashboardController::class, 'markBillingAsPaid'])->name('patient.billing.mark-paid');
    Route::get('/billings/{billingId}/pdf', [PatientDashboardController::class, 'generateBillingPdf'])->name('patient.billing.pdf');
});
