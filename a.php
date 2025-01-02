<?php
// Step 1: Generate hash password
$password = "admin123"; // Ganti dengan password yang diinginkan
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Password Hash: " . $hashedPassword;

// Step 2: Query SQL untuk insert user
// Copy hash password yang dihasilkan di atas dan gunakan dalam query berikut:
/*
INSERT INTO users (username, password) VALUES 
('nama_user', 'paste_hash_password_disini');

Contoh lengkap:
INSERT INTO users (username, password) VALUES 
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
*/
?>