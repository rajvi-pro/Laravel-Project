<?php

require 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Create a request
$request = \Illuminate\Http\Request::create(
    '/patient/get-available-slots',
    'POST',
    [
        'doctor_id' => 2,  // Aayusha Gondaliya
        'appointment_date' => '2026-05-05'  // Monday
    ],
    [],
    [],
    ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
);

// Manually bootstrap the application for testing
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the controller
$controller = new \App\Http\Controllers\Patient\PatientDashboardController();

// Call the method directly
$response = $controller->getAvailableTimeSlots($request);
$data = json_decode($response->getContent(), true);

echo "=== DOCTOR SCHEDULE TEST ===\n";
echo "Doctor ID: 2 (Aayusha Gondaliya)\n";
echo "Date: 2026-05-05 (Monday)\n";
echo "Response:\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
echo "\n";

if (!empty($data['slots'])) {
    echo "✅ SUCCESS! Found " . count($data['slots']) . " available slots\n";
    echo "\nSlot Details:\n";
    foreach ($data['slots'] as $slot) {
        echo "  - {$slot['display']} ({$slot['period']})\n";
    }
} else {
    echo "❌ ERROR: No slots returned\n";
    if (isset($data['reason'])) {
        echo "Reason: {$data['reason']}\n";
    }
    if (isset($data['debug'])) {
        echo "Debug: {$data['debug']}\n";
    }
}
