<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mendaki";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk total penjualan bulanan
$sqlPayments = "
    SELECT 
        MONTHNAME(timestamp) AS month, 
        SUM(item_price) AS total_sales 
    FROM 
        payments 
    GROUP BY 
        MONTH(timestamp)
    ORDER BY 
        MONTH(timestamp)
";
$resultPayments = $conn->query($sqlPayments);

// Menyimpan data penjualan bulanan ke array
$salesData = [];
$months = [];
if ($resultPayments->num_rows > 0) {
    while ($row = $resultPayments->fetch_assoc()) {
        $months[] = $row['month'];
        $salesData[] = $row['total_sales'];
    }
}

// Query untuk distribusi barang
$sqlProducts = "
    SELECT 
        item_name, 
        COUNT(item_name) AS item_count 
    FROM 
        payments 
    GROUP BY 
        item_name
";
$resultProducts = $conn->query($sqlProducts);

// Menyimpan data distribusi barang
$productNames = [];
$productCounts = [];
if ($resultProducts->num_rows > 0) {
    while ($row = $resultProducts->fetch_assoc()) {
        $productNames[] = $row['item_name'];
        $productCounts[] = $row['item_count'];
    }
}

// Query untuk menghitung total pengguna
$sqlTotalUsers = "SELECT COUNT(DISTINCT customer_name) AS total_users FROM payments";
$resultTotalUsers = $conn->query($sqlTotalUsers);
$totalUsers = 0;
if ($resultTotalUsers->num_rows > 0) {
    $row = $resultTotalUsers->fetch_assoc();
    $totalUsers = $row['total_users'];
}

// Query untuk menghitung total pembelian
$sqlTotalSales = "SELECT SUM(item_price) AS total_sales FROM payments";
$resultTotalSales = $conn->query($sqlTotalSales);
$totalSales = 0;
if ($resultTotalSales->num_rows > 0) {
    $row = $resultTotalSales->fetch_assoc();
    $totalSales = $row['total_sales'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Sidebar styles */
        .wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #212529;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 10px 20px;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            background: #212529;
        }

        #sidebar ul li.active > a {
            background: #212529;
        }

        /* Content styles */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #343a40;
            color: white;
        }

        /* Icon styles */
        .fas {
            margin-right: 10px;
        }
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
                <li class="active">
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li>
                    <a href="pengguna.php"><i class="fas fa-users"></i> Laporan Pengguna</a>
                </li>
                <li>
                    <a href="penyewaan.php"><i class="fas fa-shopping-cart"></i> Laporan Penyewaan</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-text">
                        Welcome, Admin
                    </span>
                </div>
            </nav>

            <!-- Cards -->
            <div class="container-fluid py-4">
                <div class="row">
                    <!-- Total Pengguna -->
                    <div class="col-md-3 mb-4">
                        <a href="pengguna.php" class="text-decoration-none">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Pengguna</h6>
                                            <h3 class="mb-0"><?php echo $totalUsers; ?></h3>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total Pembelian -->
                    <div class="col-md-3 mb-4">
                        <a href="penyewaan.php" class="text-decoration-none">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Pembelian</h6>
                                            <h3 class="mb-0">Rp <?php echo number_format($totalSales, 0, ',', '.'); ?></h3>
                                        </div>
                                        <i class="fas fa-shopping-cart fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>



                <!-- Charts -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                Statistik Penjualan
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" style="height: 672px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                Distribusi Produk
                            </div>
                            <div class="card-body">
                                <canvas id="productChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Sales Chart
        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Penjualan Bulanan',
                    data: <?php echo json_encode($salesData); ?>,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        // Product Chart
        const productChart = new Chart(document.getElementById('productChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($productNames); ?>,
                datasets: [{
                    data: <?php echo json_encode($productCounts); ?>,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            }
        });
    </script>
</body>
</html>