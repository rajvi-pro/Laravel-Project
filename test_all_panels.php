<?php
echo "Testing all four login panels as requested...\n\n";

$panels = [
    'Admin' => 'http://127.0.0.1:8000/admin/login',
    'Doctor' => 'http://127.0.0.1:8000/doctor/login', 
    'Patient' => 'http://127.0.0.1:8000/patient/login',
    'Staff' => 'http://127.0.0.1:8000/staff/login'
];

$allWorking = true;

foreach ($panels as $name => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code == 200 && strpos($response, 'form') !== false) {
        echo "[$name Panel] ✓ Login panel working - HTTP $code\n";
    } else {
        echo "[$name Panel] ✗ Login panel NOT working - HTTP $code\n";
        $allWorking = false;
    }
}

echo "\n";

// Test database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'wellcare');
if ($conn->connect_error) {
    echo "[Database] ✗ NOT connected\n";
    $allWorking = false;
} else {
    echo "[Database] ✓ Connected to wellcare\n";
    $conn->close();
}

echo "\n";

if ($allWorking) {
    echo "✅ ALL LOGIN PANELS WORKING WITH DATABASE CONNECTION\n";
    echo "User request fully resolved!\n";
} else {
    echo "⚠️ Issue detected - see above\n";
}
?>
