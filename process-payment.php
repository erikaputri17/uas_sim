<?php
// Koneksi ke database MySQL
$host = "localhost";
$user = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$database = "mendaki"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customerName'];
    $item_name = $_POST['itemName'];
    $item_price = $_POST['itemPrice'];
    $rental_start = $_POST['rentalStart'];
    $rental_end = $_POST['rentalEnd'];
    $payment_method = $_POST['paymentMethod'];

    // Query untuk memasukkan data ke tabel "payments"
    $sql = "INSERT INTO payments (customer_name, item_name, item_price, rental_start, rental_end, payment_method)
            VALUES ('$customer_name', '$item_name', '$item_price', '$rental_start', '$rental_end', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pemesanan berhasil disimpan!'); window.location.href='sukses.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>