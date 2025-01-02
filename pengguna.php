<!-- pengguna.php -->
<?php
// Mengaktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Informasi koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "mendaki";

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data dari tabel users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Debugging query
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .wrapper { display: flex; width: 100%; }
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar.active { margin-left: -250px; }
        #sidebar .sidebar-header { padding: 20px; background: #212529; }
        #sidebar ul.components { padding: 20px 0; margin: 0;}
        #sidebar ul li a { padding: 10px 20px; display: block; color: #fff; text-decoration: none; }
        #sidebar ul li a:hover { background: #212529; }
        #sidebar ul li.active > a { background: #212529; }
        #content { width: 100%;  }
        .card { margin-bottom: 20px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Admin Dashboard</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="active">
                    <a href="pengguna.php"><i class="fas fa-users"></i> Laporan Pengguna</a>
                </li>
                <li>
                    <a href="penyewaan.php"><i class="fas fa-shopping-cart"></i> Laporan Penyewaan</a>
                </li>               
            </ul>
        </nav>

        <!-- Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="sidebarCollapse">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-text">Laporan Pengguna</span>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="mb-0">Daftar Pengguna</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>username</th>
                                        <th>email</th>
                                        <th>role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['username']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['role']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>