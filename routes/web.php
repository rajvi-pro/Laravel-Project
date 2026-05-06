<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Staff\PublicStaffController;
use App\Models\Patient;
use App\Models\Doctor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Hybrid Approach: Main routes file that imports role-based route files
| This keeps web.php clean while maintaining organized route structure
|
*/

// Public Welcome Page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public staff listing
Route::get('/staff', [PublicStaffController::class, 'index'])->name('staff.index');
require __DIR__ . '/admin.php';
require __DIR__ . '/doctor.php';
require __DIR__ . '/patient.php';
require __DIR__ . '/staff.php';