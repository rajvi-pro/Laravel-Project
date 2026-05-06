<?php

require 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Test multiple doctors
$tests = [
    ['doctor_id' => 1, 'name' => 'Jyoti Virwani'],      // Morning shift
    ['doctor_id' => 2, 'name' => 'Aayusha Gondaliya'],  // Afternoon shift
    ['doctor_id' => 3, 'name' => 'Vilas Patel'],        // Full day with lunch break
];

// Bootstrap the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = new \App\Http\Controllers\Patient\PatientDashboardController();

echo "=== TIMESLOT AVAILABILITY TEST ===\n";
echo "Date: 2026-05-06 (Tuesday - All doctors work)\n\n";

foreach ($tests as $test) {
    $request = \Illuminate\Http\Request::create(
        '/patient/get-available-slots',
        'POST',
        [
            'doctor_id' => $test['doctor_id'],
            'appointment_date' => '2026-05-06'  // Tuesday
        ],
        [],
        [],
        ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
    );

    $response = $controller->getAvailableTimeSlots($request);
    $data = json_decode($response->getContent(), true);

    echo "Doctor: {$test['name']}\n";
    echo "Working Hours: {$data['workingHours']}\n";
    
    if (!empty($data['slots'])) {
        echo "Available Slots: " . count($data['slots']) . "\n";
        echo "Slots:\n";
        foreach ($data['slots'] as $slot) {
            echo "  ✓ {$slot['display']}\n";
        }
    } else {
        echo "❌ No slots available\n";
    }
    echo "\n";
}

echo "=== TEST COMPLETE ===\n";
