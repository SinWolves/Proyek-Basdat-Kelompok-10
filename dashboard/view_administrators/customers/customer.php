<?php
  include '../../conn.php';

  // Mulai session untuk notifikasi
  session_start();

  // Inisialisasi variabel notifikasi
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Hapus notifikasi setelah ditampilkan
  unset($_SESSION['error'], $_SESSION['success']);

  // Variabel untuk edit mode
  $edit_id = isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : null;

  if($_SERVER['REQUEST_METHOD']==='POST'){
    // Tambahkan data baru
    if (isset($_POST['submit_add'])) {
      try{
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $telepon = htmlspecialchars($_POST['telepon']);
        $username = htmlspecialchars($_POST['username']);
        $tanggal_lahir = htmlspecialchars($_POST['birth']);
        
        $stmt = $pdo->prepare("INSERT INTO customer(nama, email, telepon, username, tanggal_lahir) VALUES (:nama, :email, :telepon, :username, :tanggal_lahir)");
        $stmt->bindParam(':nama', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
      
        $stmt->execute();

        $_SESSION['success'] = "New data added successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 
      }catch (PDOException $e) {
        $_SESSION['error'] = "Error adding data: " . $e->getMessage();
      }
    }
    
    // Menghapus data berdasarkan ID
    if (isset($_POST['submit_delete'])) {
      $id = htmlspecialchars($_POST['id']);

      try {
          $stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          $_SESSION['success'] = "Data deleted successfully!";
      } catch (PDOException $e) {
          $_SESSION['error'] = "Error deleting data: " . $e->getMessage();
      }

      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }

    // Proses update data
    if (isset($_POST['submit_edit'])) {
      try {
        $id = htmlspecialchars($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $telepon = htmlspecialchars($_POST['telepon']);
        $username = htmlspecialchars($_POST['username']);
        $tanggal_lahir = htmlspecialchars($_POST['birth']);
        
        $stmt = $pdo->prepare("UPDATE customer SET nama = :nama, email = :email, telepon = :telepon, username = :username, tanggal_lahir = :tanggal_lahir WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
      
        $stmt->execute();

        $_SESSION['success'] = "Data updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 
      }catch (PDOException $e) {
        $_SESSION['error'] = "Error updating data: " . $e->getMessage();
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="customer.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
</head>
<body>
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
      <li><a href="customer.php">Customer Management</a></li>
      <li><a href="../bookings/booking.php">Booking Management</a></li>
      <li><a href="../payments/payment.php">Payment Management</a></li>
      <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Customer Management</h1>


<!-- Notifikasi -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <?php if (!empty($_SESSION['isDelete']) && $_SESSION['isDelete']): ?>
        <!-- Notifikasi delete berhasil (warna merah) -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php else: ?>
        <!-- Notifikasi umum (warna hijau) -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>


<!-- Replace the existing code above the table with this -->
<div class="container">
  <div class="row mb-3">
    <div class="col-12 text-start">
      <button id="toggleAddCustomerForm" class="btn btn" style="background-color:#15274b;color:#fff">+ Add New Customer</button>
    </div>
  </div>


<div class="container">
      <div class="row">
        <div class="col-12"> 
          <table id="table" class="table table-bordered w-100">
            <thead class="table-primary">
                <tr>
                  <th>ID Customer</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Username</th>
                  <th>Date Of Birth</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $data = [];
                try {
                    $stmt = $pdo->query("SELECT * FROM customer ORDER BY id");
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $message = "Error fetching data: " . $e->getMessage();
                }
              ?>
              <?php if(!empty($data)) : ?>
                <?php foreach($data as $item): ?>
                  <?php if($edit_id == $item['id']): ?>
                    <!-- Edit Row -->
                    <tr class="edit-row">
                      <form action="" method="POST">
                        <input type="hidden" name="submit_edit" value="1">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><input name="name" type="text" value="<?php echo htmlspecialchars($item['nama']); ?>" required></td>
                        <td><input name="email" type="email" value="<?php echo htmlspecialchars($item['email']); ?>" required></td>
                        <td><input name="telepon" type="tel" value="<?php echo htmlspecialchars($item['telepon']); ?>" required></td>
                        <td><input name="username" type="text" value="<?php echo htmlspecialchars($item['username']); ?>" required></td>
                        <td><input name="birth" type="date" value="<?php echo htmlspecialchars($item['tanggal_lahir']); ?>" required></td>
                        <td>
                          <button type="submit" class="btn sm me-1" style="background-color:#15274b;color:#fff">Save</button>
                          <a href="customer.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </td>
                      </form>
                    </tr>
                  <?php else: ?>
                    <!-- Normal Row -->
                    <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['nama']); ?></td>
                        <td><?php echo htmlspecialchars($item['email']); ?></td>
                        <td><?php echo htmlspecialchars($item['telepon']); ?></td>
                        <td><?php echo htmlspecialchars($item['username']); ?></td>
                        <td><?php echo htmlspecialchars($item['tanggal_lahir']); ?></td>
                        <td>
                          <a href="customer.php?edit=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-success btn-sm me-1">Edit</a>
                          <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display:inline;">
                            <input type="hidden" name="submit_delete" value="1">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                          </form>
                        </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Add New Customer Form (Unchanged from previous version) -->
        <div id="serviceFormContainer">
          <form action="" method="POST">
          <input type="hidden" name="submit_add" value="1">
          <div class="border border-black p-3" id="serviceForm">
            <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add New Customer</header>
            <div class="d-flex align-items-center mb-3">
              <label for="idcustomer" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Name</label>
              <input name="name" type="text" id="idcustomer" class="form-control flex-grow-1" required>
            </div>
            <div class="d-flex align-items-center mb-3">
              <label for="email" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Email</label>
              <input name="email" type="email" id="email" class="form-control flex-grow-1" required>
            </div>
            <div class="d-flex align-items-center mb-3">
              <label for="telepon" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Phone Number</label>
              <input name="telepon" type="tel" id="telepon" class="form-control flex-grow-1" required>
            </div>
            <div class="d-flex align-items-center mb-3">
              <label for="username" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Username</label>
              <input name="username" type="text" id="username" class="form-control flex-grow-1" required>
            </div>
            <div class="d-flex align-items-center mb-3">
              <label for="birth" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Date of Birth</label>
              <input name="birth" type="date" id="birth" class="form-control flex-grow-1" required>
            </div>
            
            <div class="text-end">
              <button type="submit" class="btn btn rounded-3 fw-bold" id="addingService" style="background-color:#15274b;color:#fff">Save</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div> 

    <script src="customer.js"></script>
    <script src="../../sidebar.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>