<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "mendaki");

// Tambahkan error checking untuk koneksi database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fungsi untuk login
function login($username, $password) {
    global $conn;
    
    // Validasi input
    if (empty($username) || empty($password)) {
        return false;
    }
    
    // Query untuk mendapatkan data pengguna
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        error_log("Prepare statement failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect berdasarkan role
            switch ($user['role']) {
                case 'admin':
                    header("Location: dashboardadmin.html");
                    break;
                case 'customer':
                    header("Location: equipment.html");
                    break;
                default:
                    header("Location: dashboard.html");
                    break;
            }
            exit(); // Penting: tambahkan exit setelah header redirect
            return true;
        }
    }
    return false;
}

// Fungsi untuk memproses login
function prosesLogin() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (!login($username, $password)) {
            echo "<script>
                alert('Username atau password salah');
                window.location.href='login.php';
            </script>";
        }
    }
}

// Panggil fungsi proses login
prosesLogin();
?>