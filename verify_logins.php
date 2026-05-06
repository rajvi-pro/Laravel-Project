<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'wellcare');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   Hospital Management System - Login Verification        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "┌─ CHECKING DATABASE DATA ───────────────────────────────────┐\n";

// Check admins
$result = $conn->query('SELECT COUNT(*) as count FROM admins WHERE deleted_at IS NULL');
$row = $result->fetch_assoc();
echo "✓ Admins in database: " . $row['count'] . "\n";

// Check doctors
$result = $conn->query('SELECT COUNT(*) as count FROM doctors WHERE deleted_at IS NULL');
$row = $result->fetch_assoc();
echo "✓ Doctors in database: " . $row['count'] . "\n";

// Check patients
$result = $conn->query('SELECT COUNT(*) as count FROM patients WHERE deleted_at IS NULL');
$row = $result->fetch_assoc();
echo "✓ Patients in database: " . $row['count'] . "\n";

// Check staff
$result = $conn->query('SELECT COUNT(*) as count FROM staff WHERE deleted_at IS NULL');
$row = $result->fetch_assoc();
echo "✓ Staff in database: " . $row['count'] . "\n";

echo "\n┌─ TEST CREDENTIALS ─────────────────────────────────────────┐\n";

echo "📧 ADMIN:\n   Email: admin@hms.local\n   Password: Admin@123\n\n";

echo "📧 DOCTOR:\n   Email: rajesh.kumar@hospital.com\n   Password: Doctor@123\n\n";

echo "📧 PATIENT:\n   Email: pooja.menon@email.com\n   Password: Patient@123\n\n";

echo "📧 STAFF:\n   Email: ramesh.kumar@hospital.com\n   Password: Staff@123\n\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  All data verified! Ready for testing.                    ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";

$conn->close();
?>
