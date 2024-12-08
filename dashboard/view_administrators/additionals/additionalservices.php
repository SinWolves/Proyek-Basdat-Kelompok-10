<?php
// Menyertakan file koneksi database
include '../../conn.php';

// Mulai session untuk notifikasi
session_start();

// Inisialisasi variabel notifikasi
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

// Hapus notifikasi setelah ditampilkan
unset($_SESSION['error'], $_SESSION['success']);

$edit_id = isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menambahkan data baru
    if (isset($_POST['submit_add'])) {
        // Mencegah SQL injection dengan htmlspecialchars dan prepared statements
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = htmlspecialchars($_POST['price']);

        try {
            // Prepare statement untuk memasukkan data
            $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);

            // Eksekusi query
            $stmt->execute();

            // Pesan sukses
            $_SESSION['success'] = "New service added successfully!";

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error adding service: " . $e->getMessage();
        }

        // Redirect untuk mencegah form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Menghapus data berdasarkan ID
    if (isset($_POST['submit_delete'])) {
        $id = htmlspecialchars($_POST['id']);

        try {
            // Prepare statement untuk menghapus data
            $stmt = $pdo->prepare("DELETE FROM additional_service WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Eksekusi query
            $stmt->execute();

            // Pesan sukses
            $_SESSION['success'] = "Service deleted successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting service: " . $e->getMessage();
        }

        // Redirect untuk mencegah form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Proses update data
    if (isset($_POST['submit_edit'])) {
        try {
          $id = htmlspecialchars($_POST['id']);
          $name = htmlspecialchars($_POST['name']);
          $description = htmlspecialchars($_POST['description']);
          $price = htmlspecialchars($_POST['price']);
          
          $stmt = $pdo->prepare("UPDATE additional_service SET name = :name, description = :description, price = :price WHERE id = :id");
          $stmt->bindParam(':id', $id);
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':description', $description);
          $stmt->bindParam(':price', $price);
        
          $stmt->execute();
  
          $_SESSION['success'] = "Data updated successfully!";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit(); 
        }catch (PDOException $e) {
          $_SESSION['error'] = "Error updating data: " . $e->getMessage();
        }
      }
}

// Mengambil semua data dari tabel additional_service
try {
    $stmt = $pdo->query("SELECT id, name, description, price FROM additional_service ORDER BY id DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching services: " . $e->getMessage();
    $services = [];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="addition.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
    <style>
       .form-control {
        border: 1px solid #0d335d;
        margin-bottom: 10px;
      }

      .section-title {
        font-size: 18px;
        color: #000000;
      }
    </style>
</head>

     <!-- Navbar -->
     <nav class="navbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button id="menu-toggle" class="menu-toggle me-3">
                <i class="fas fa-bars"></i> 
            </button>
        </div>
        <div class="d-flex align-items-center">
            <!-- Search Bar -->
            <input type="text" id="search-table" class="form-control me-3" onkeyup="searchTable('table')" 
                placeholder="Search..." style="max-width: 300px;">
            <!-- Logout -->
            <a href="../../view_customers/login.php" class="logout">Logout</a>
        </div>
    </nav>
  <!-- Overlay and Sidebar -->
  <div class="overlay"></div>
  <div class="sidebar">
    <div class="close-icon">
      <i class="fas fa-times"></i>
    </div>
    <div class="admin-section  d-flex align-items-center mb-4">
      <i class="fas fa-user-circle fa-3x text-white me-3"></i>
      <h3 class="text-white mb-0 nav-links">Administrator</h3>
    </div>
    <ul class="nav-links">
      <li><a href="../dashboard.php">Dashboard</a></li>
      <li><a href="../rooms/room.php">Room Management</a></li>
      <li><a href="../customers/customer.php">Customer Management</a></li>
      <li><a href="../bookings/booking.php">Booking Management</a></li>
      <li><a href="../payments/payment.php">Payment Management</a></li>
      <li><a href="additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>


<div class="container">
    <h1>Manage Additional Services</h1>

    <!-- Notifikasi -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <!-- Form Tambah Data -->
    <div class="container border border-black row" id="additForm">
    <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add Additional Service</header>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="submit_add" value="1">
                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="text-end">
                <button type="submit" class="btn btn-primary" id="addingService">Submit</button>
                
            </form>

        
            </div>

        </div>
    </div>

    <!-- Tabel Data -->
    <div class="container">
    <div class="row">
      <div class="col-md-6">
        <table id="table" class="table table-bordered">
          <thead class="table-primary">
                        <th>Service ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No services available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $service): ?>
                            <?php if($edit_id == $service['id']): ?>
                                <!-- Edit Row -->
                                <tr class="edit-row">
                                <form action="" method="POST">
                                    <input type="hidden" name="submit_edit" value="1">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
                                    <td><?php echo htmlspecialchars($service['id']); ?></td>
                                    <td><input name="name" type="text" value="<?php echo htmlspecialchars($service['name']); ?>" required></td>
                                    <td><input name="description" type="text" value="<?php echo htmlspecialchars($service['description']); ?>" required></td>
                                    <td><input name="price" type="text" value="<?php echo htmlspecialchars($service['price']); ?>" required></td>
                                    <td>
                                    <button type="submit" class="btn btn-success btn-sm me-1">Save</button>
                                    <a href="additionalservices.php" class="btn btn-secondary btn-sm">Cancel</a>
                                    </td>
                                </form>
                                </tr>
                            <?php else: ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['id']); ?></td>
                                <td><?php echo htmlspecialchars($service['name']); ?></td>
                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                <td><?php echo htmlspecialchars($service['price']); ?></td>
                                <td>
                                    <!-- Form Hapus Data -->
                                    <a href="additionalservices.php?edit=<?php echo htmlspecialchars($service['id']); ?>" class="btn btn-success btn-sm me-1">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display:inline;">
                                        <input type="hidden" name="submit_delete" value="1">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../../sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
        function searchTable(tableId) {
                const input = document.getElementById(`search-${tableId}`).value.toLowerCase();
                const rows = document.getElementById(tableId).querySelector('tbody').getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length; j++) { // Include all columns in the search
                        if (cells[j].textContent.toLowerCase().includes(input)) {
                            found = true;
                            break;
                        }
                    }

                    rows[i].style.display = found ? '' : 'none';
                }
            }
    </script>

</body>
</html>
