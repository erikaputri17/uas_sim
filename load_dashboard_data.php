<?php
header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mendaki';

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Total Users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$total_users = $userResult->fetch_assoc()['total_users'] ?? 0;

// Total Payments
$paymentQuery = "SELECT COUNT(*) AS total_payments FROM payments";
$paymentResult = $conn->query($paymentQuery);
$total_payments = $paymentResult->fetch_assoc()['total_payments'] ?? 0;

// Total Revenue
$revenueQuery = "SELECT SUM(amount) AS total_revenue FROM payments";
$revenueResult = $conn->query($revenueQuery);
$total_revenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;

// Hasil sebagai JSON
echo json_encode([
    'total_users' => $total_users,
    'total_payments' => $total_payments,
    'total_revenue' => $total_revenue
]);

$conn->close();
?>
