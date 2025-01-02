<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "mendaki");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch items
$items = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .wrapper { display: flex; width: 100%; }
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar.active { margin-left: -250px; }
        #sidebar .sidebar-header { padding: 20px; background: #212529; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 10px 20px; display: block; color: #fff; text-decoration: none; }
        #sidebar ul li a:hover { background: #212529; }
        #sidebar ul li.active > a { background: #212529; }
        #content { width: 100%; padding: 20px; }
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
                <li>
                    <a href="pengguna.php"><i class="fas fa-users"></i> Laporan Pengguna</a>
                </li>
                <li>
                    <a href="pembelian.php"><i class="fas fa-shopping-cart"></i> Laporan Pembelian</a>
                </li>
                <li>
                    <a href="penjualan.php"><i class="fas fa-cash-register"></i> Laporan Penjualan</a>
                </li>
                <li class="active">
                    <a href="barang.php"><i class="fas fa-box"></i> Laporan Barang</a>
                </li>
                <li>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                    <span class="navbar-text">Inventory Management</span>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="mb-0">Item List</h5>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                    <i class="fas fa-plus"></i> Add New Item
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($item = mysqli_fetch_assoc($items)): ?>
                                    <tr>
                                        <td><?php echo $item['id']; ?></td>
                                        <td>
                                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo $item['stock']; ?></td>
                                        <td>Rp <?php echo number_format($item['price']); ?></td>
                                        <td>
                                    <!-- barang.php (continued) -->
                                    <span class="badge <?php echo $item['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $item['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                    data-bs-target="#editItemModal" 
                                                    data-id="<?php echo $item['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteItem(<?php echo $item['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addItemForm" action="process_item.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                <option value="Tenda">Tenda</option>
                                <option value="Sleeping Bag">Sleeping Bag</option>
                                <option value="Carrier">Carrier</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control" name="stock" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control" name="price" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editItemForm" action="process_item.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="item_id" id="editItemId">
                    <div class="modal-body">
                        <!-- Same fields as Add Item Modal -->
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" id="editCategory" required>
                                <option value="Tenda">Tenda</option>
                                <option value="Sleeping Bag">Sleeping Bag</option>
                                <option value="Carrier">Carrier</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control" name="stock" id="editStock" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control" name="price" id="editPrice" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Image (leave empty to keep current)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Delete item function
        function deleteItem(itemId) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('process_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=delete&item_id=' + itemId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting item');
                    }
                });
            }
        }

        // Load item data for editing
        document.getElementById('editItemModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const itemId = button.getAttribute('data-id');
            
            // Fetch item data
            fetch('get_item.php?id=' + itemId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editItemId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editCategory').value = data.category;
                    document.getElementById('editStock').value = data.stock;
                    document.getElementById('editPrice').value = data.price;
                    document.getElementById('editDescription').value = data.description;
                });
        });
    </script>
</body>
</html>