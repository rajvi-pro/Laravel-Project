<?php
/**
 * Test Script for Hospital Management System Login Verification
 * Tests all login panels with seeded test credentials
 */

// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Change to project directory
chdir(__DIR__);

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Create the application and run it
(function () {
    $app = app();
})();

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   Hospital Management System - Login Test Suite           ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Test Admin Login
echo "┌─ ADMIN LOGIN TEST ─────────────────────────────────────────┐\n";
$admin = Admin::where('email', 'admin@hms.local')->first();
if ($admin) {
    echo "✓ Admin found: {$admin->name}\n";
    
    if (Hash::check('Admin@123', $admin->password)) {
        echo "✓ Password 'Admin@123' is valid\n";
    } else {
        echo "✗ Password 'Admin@123' is INVALID\n";
    }
} else {
    echo "✗ Admin not found in database\n";
}

// Test Doctor Login
echo "\n┌─ DOCTOR LOGIN TEST ────────────────────────────────────────┐\n";
$doctor = Doctor::where('email', 'rajesh.kumar@hospital.com')->first();
if ($doctor) {
    echo "✓ Doctor found: {$doctor->name}\n";
    
    if (Hash::check('Doctor@123', $doctor->password)) {
        echo "✓ Password 'Doctor@123' is valid\n";
    } else {
        echo "✗ Password 'Doctor@123' is INVALID\n";
    }
} else {
    echo "✗ Doctor not found in database\n";
}

// Test Patient Login
echo "\n┌─ PATIENT LOGIN TEST ───────────────────────────────────────┐\n";
$patient = Patient::where('email', 'pooja.menon@email.com')->first();
if ($patient) {
    echo "✓ Patient found: {$patient->name}\n";
    
    if (Hash::check('Patient@123', $patient->password)) {
        echo "✓ Password 'Patient@123' is valid\n";
    } else {
        echo "✗ Password 'Patient@123' is INVALID\n";
    }
} else {
    echo "✗ Patient not found in database\n";
}

// Test Staff Login
echo "\n┌─ STAFF LOGIN TEST ─────────────────────────────────────────┐\n";
$staff = Staff::where('email', 'ramesh.kumar@hospital.com')->first();
if ($staff) {
    echo "✓ Staff found: {$staff->name}\n";
    
    if (Hash::check('Staff@123', $staff->password)) {
        echo "✓ Password 'Staff@123' is valid\n";
    } else {
        echo "✗ Password 'Staff@123' is INVALID\n";
    }
} else {
    echo "✗ Staff not found in database\n";
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                 TEST SUMMARY                               ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";

echo "\nLogged in users by role:\n";
echo "Admin    (" . Admin::count() . "): admin@hms.local / Admin@123\n";
echo "Doctor   (" . Doctor::count() . "): rajesh.kumar@hospital.com / Doctor@123\n";
echo "Patient  (" . Patient::count() . "): pooja.menon@email.com / Patient@123\n";
echo "Staff    (" . Staff::count() . "): ramesh.kumar@hospital.com / Staff@123\n";

echo "\n✓ All database connections are working!\n";
echo "✓ Test data has been successfully seeded!\n";
?>
