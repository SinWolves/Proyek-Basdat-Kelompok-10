<?php
  include '../../conn.php';


  // Inisialisasi variabel notifikasi
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';


  // Initialize message variable
  $message = '';
  $messageType = '';

  // Handle delete operation
  if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $staffId = $_GET['delete'];
    try {
      $stmt = $pdo->prepare("DELETE FROM staff WHERE id = :id");
      $stmt->bindParam(':id', $staffId, PDO::PARAM_INT);
      $stmt->execute();

      // Set error message for delete 
      $message = "Data berhasil dihapus!";
      $messageType = "danger"; 
    } catch (Exception $e) {
      // Set error message
      $message = "Gagal menghapus data: " . $e->getMessage();
      $messageType = "danger";
    }
  }

  // Handle add staff operation
  if($_SERVER['REQUEST_METHOD']==='POST'){
    try {
      // Sanitize input
      $nama = htmlspecialchars($_POST['nama']);
      $departemen = htmlspecialchars($_POST['departemen']);
      $telepon = htmlspecialchars($_POST['telepon']);
      $alamat = htmlspecialchars($_POST['alamat']);
      
      // Prepare and execute insert
      $stmt = $pdo->prepare("INSERT INTO staff(nama, departemen, telepon, alamat) VALUES (:nama, :departemen, :telepon, :alamat)");
      $stmt->bindParam(':nama', $nama);
      $stmt->bindParam(':departemen', $departemen);
      $stmt->bindParam(':telepon', $telepon);
      $stmt->bindParam(':alamat', $alamat);
      $stmt->execute();


      // Prevent form resubmission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit(); 
    } catch (Exception $e) {
      // Set error message
      $message = "Gagal menambahkan data: " . $e->getMessage();
      $messageType = "danger";
    }
  }

  // Fetch staff data
  $staffs = [];
  try {
    $stmt = $pdo->query("SELECT * FROM staff ORDER BY id");
    $staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (Exception $e) {
    $message = "Gagal mengambil data: " . $e->getMessage();
    $messageType = "danger";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit - Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="staff.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-between">
        <button id="menu-toggle" class="menu-toggle">
            <i class="fas fa-bars"></i> 
        </button>
        <div class="logout-container">
            <a href="../../view_customers/login.html" class="logout">Logout</a>
        </div>
    </nav>

     <!-- Navbar -->
   <nav class="navbar d-flex justify-content-between">
    <button id="menu-toggle" class="menu-toggle">
      <i class="fas fa-bars"></i> 
    </button>
    <div class="logout-container">
      <a href="../../view_customers/login.php" class="logout">Logout</a>
    </div>
  </nav>


    <!-- Sidebar -->
    <div class="overlay"></div>
    <div class="sidebar">
        <div class="close-icon">
            <i class="fas fa-times"></i>
        </div>
        <div class="admin-section d-flex align-items-center mb-4">
            <i class="fas fa-user-circle fa-3x text-white me-3"></i>
            <h3 class="text-white mb-0 nav-links">Administrator</h3>
        </div>
        <ul class="nav-links">
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../rooms/room.php">Room Management</a></li>
            <li><a href="../customers/customer.php">Customer Management</a></li>
            <li><a href="../bookings/booking.php">Booking Management</a></li>
            <li><a href="../payments/payment.php">Payment Management</a></li>
            <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
            <li><a href="staff.php">Staff Management</a></li>
            <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
            <li><a href="../managers/manager.php">Manager Management</a></li>
        </ul>
    </div>

    <div class="container">
        <!-- Notification Alert -->
        <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

        <h1 class="mb-4">Staff Management</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Staff</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($staffs)) : ?>
                            <?php foreach($staffs as $staff): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['id']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['departemen']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['telepon']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['alamat']); ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $staff['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  

    <form action="" method="POST">
        <div class="container border border-black row" id="staffForm">
            <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Tambah Staff</header> 
            <div class="col-md-6 d-flex align-items-center">
                <label for="staffName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Nama</label>
                <input name="nama" type="text" id="staffName" class="form-control flex-grow-1" value="" required><br>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <label for="departmentName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Departemen</label>
                <input name="departemen" type="text" id="departmentName" class="form-control flex-grow-1" value="" required><br>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <label for="staffNumber" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Nomor Telepon</label>
                <input name="telepon" type="text" id="staffNumber" class="form-control flex-grow-1" value="" required><br>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <label for="staffAddress" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Alamat</label>
                <input name="alamat" type="text" id="staffAddress" class="form-control flex-grow-1" value="" required><br>
            </div>
            <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingStaff">Simpan</button>
        </div>
    </form>

    <script src="../../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>