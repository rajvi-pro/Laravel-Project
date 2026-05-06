<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPatientController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminBillingController;

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Protected Admin Routes
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('admin.export.excel');
    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('admin.export.pdf');

    // Patient Management
    Route::resource('patients', AdminPatientController::class, ['as' => 'admin']);

    // Doctor Management
    Route::resource('doctors', AdminDoctorController::class, ['as' => 'admin']);

    // Doctor Schedule Management (read-only for admin)
    Route::get('/schedules', [AdminDashboardController::class, 'viewSchedules'])->name('admin.schedules');
    Route::get('/doctors/{doctorId}/schedule', [AdminDashboardController::class, 'viewDoctorSchedule'])->name('admin.doctor.schedule');
    Route::get('/schedules/{scheduleId}/edit', [AdminDashboardController::class, 'editSchedule'])->name('admin.schedule-edit');
    Route::put('/schedules/{scheduleId}', [AdminDashboardController::class, 'updateSchedule'])->name('admin.schedule-update');
    Route::delete('/schedules/{scheduleId}', [AdminDashboardController::class, 'deleteSchedule'])->name('admin.schedule-delete');


    // Staff Management
    Route::resource('staff', AdminStaffController::class, ['as' => 'admin']);
    
    // Billing Management
    Route::resource('billing', AdminBillingController::class, ['as' => 'admin']);
    Route::get('/billing-analytics', [AdminBillingController::class, 'analytics'])->name('admin.billing.analytics');
    
    // Appointment Management (admin can view and change appointment time)
    Route::resource('appointments', \App\Http\Controllers\Admin\AdminAppointmentController::class, ['as' => 'admin'])->only(['index','edit','update','show']);
    Route::get('/appointments-slots/{doctorId}/{date}', [\App\Http\Controllers\Admin\AdminAppointmentController::class, 'getAvailableSlots'])->name('admin.appointments.slots');
    
    // Search
    Route::get('/search', [AdminDashboardController::class, 'search'])->name('admin.search');
    Route::get('/search/suggestions', [AdminDashboardController::class, 'searchSuggestions'])->name('admin.search.suggestions');

    // Doctor Unavailability Management
    Route::get('/unavailable-doctors', [AdminDashboardController::class, 'unavailableDoctors'])->name('admin.unavailable-doctors');
    Route::get('/affected-appointments/{unavailabilityId}', [AdminDashboardController::class, 'affectedAppointments'])->name('admin.affected-appointments');
    Route::post('/reschedule-appointment/{appointmentId}', [AdminDashboardController::class, 'rescheduleAppointment'])->name('admin.reschedule-appointment');
});
