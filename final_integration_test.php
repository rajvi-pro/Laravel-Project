<?php
/**
 * Final Integration Test
 * Complete end-to-end login system verification
 */

echo "FINAL INTEGRATION TEST\n";
echo "======================\n\n";

// 1. Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'wellcare');
if ($conn->connect_error) {
    echo "FAIL: Database connection failed\n";
    exit(1);
}
echo "PASS: Database connected\n";

// 2. Check admin user exists
$admin = $conn->query("SELECT password FROM admins WHERE email='admin@hms.local' LIMIT 1")->fetch_assoc();
if (!$admin) {
    echo "FAIL: Admin user not found\n";
    exit(1);
}
echo "PASS: Admin user found\n";

// 3. Verify password hash
if (password_verify('Admin@123', $admin['password'])) {
    echo "PASS: Admin password verified\n";
} else {
    echo "FAIL: Admin password verification failed\n";
    exit(1);
}

// 4. Check all user roles exist
$counts = [
    'admins' => 0,
    'doctors' => 0,
    'patients' => 0,
    'staff' => 0
];

foreach ($counts as $table => $_ ) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM $table WHERE deleted_at IS NULL");
    $row = $result->fetch_assoc();
    $counts[$table] = $row['cnt'];
}

if ($counts['admins'] >= 2 && $counts['doctors'] >= 6 && $counts['patients'] >= 5 && $counts['staff'] >= 5) {
    echo "PASS: All user roles populated (" . implode(", ", $counts) . ")\n";
} else {
    echo "FAIL: Not all user roles populated correctly\n";
    exit(1);
}

// 5. HTTP endpoint test
$response = @file_get_contents('http://127.0.0.1:8000/admin/login');
if ($response && strpos($response, 'form') !== false) {
    echo "PASS: Admin login endpoint accessible\n";
} else {
    echo "FAIL: Admin login endpoint not responding\n";
    exit(1);
}

// 6. CSRF token in response
if (strpos($response, '_token') !== false) {
    echo "PASS: CSRF token present in login form\n";
} else {
    echo "FAIL: CSRF token missing\n";
    exit(1);
}

// 7. Demo credentials displayed
if (strpos($response, 'admin@hms.local') !== false && strpos($response, 'Admin@123') !== false) {
    echo "PASS: Demo credentials displayed correctly\n";
} else {
    echo "FAIL: Demo credentials not displayed\n";
    exit(1);
}

$conn->close();

echo "\n======================\n";
echo "ALL TESTS PASSED\n";
echo "System is fully operational\n";
?>
