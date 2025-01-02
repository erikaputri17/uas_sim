<?php
header('Content-Type: application/json');

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "mendaki";

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Data penjualan per bulan dari tabel payments
$salesQuery = "
    SELECT MONTH(tanggal_pembayaran) AS bulan, SUM(jumlah) AS total_penjualan
    FROM payments
    GROUP BY MONTH(tanggal_pembayaran)";
$salesResult = $conn->query($salesQuery);
$salesData = [];
while ($row = $salesResult->fetch_assoc()) {
    $salesData[] = [
        'bulan' => $row['bulan'],
        'total_penjualan' => $row['total_penjualan']
    ];
}

// Data distribusi pengguna dari tabel users
$userDistributionQuery = "
    SELECT MONTH(tanggal_registrasi) AS bulan, COUNT(*) AS jumlah_pengguna
    FROM users
    GROUP BY MONTH(tanggal_registrasi)";
$userDistributionResult = $conn->query($userDistributionQuery);
$userData = [];
while ($row = $userDistributionResult->fetch_assoc()) {
    $userData[] = [
        'bulan' => $row['bulan'],
        'jumlah_pengguna' => $row['jumlah_pengguna']
    ];
}

// Gabungkan data menjadi JSON
$data = [
    'sales' => $salesData,
    'users' => $userData
];

$conn->close();
echo json_encode($data);
?>
