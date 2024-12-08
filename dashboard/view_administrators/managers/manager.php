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
    if (isset($_POST['submit_add'])) {
    //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
      $nama = htmlspecialchars($_POST['nama']);
      $departemen = htmlspecialchars($_POST['departemen']);
      $telepon = htmlspecialchars($_POST['telepon']);
      $alamat = htmlspecialchars($_POST['alamat']);
      
      try{
        //prepare agar tidak terjadi SQL injection
        $stmt = $pdo->prepare("INSERT INTO manajer(nama, departemen, telepon, alamat) VALUES (:nama, :departemen, :telepon, :alamat)");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':departemen', $departemen);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':alamat', $alamat);
      
        //jalankan kode
        $stmt->execute();

        // Pesan sukses
        $_SESSION['success'] = "New data added successfully!";
        //agar submit tidak diulangi ketika web di refresh
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
          // Prepare statement untuk menghapus data
          $stmt = $pdo->prepare("DELETE FROM manajer  WHERE id = :id");
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);

          // Eksekusi query
          $stmt->execute();

          // Pesan sukses
          $_SESSION['success'] = "data deleted successfully!";
      } catch (PDOException $e) {
          $_SESSION['error'] = "Error deleting data: " . $e->getMessage();
      }

      // Redirect untuk mencegah form resubmission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } 

    // Proses update data
    if (isset($_POST['submit_edit'])) {
      try {
        $id = htmlspecialchars($_POST['id']);
        $nama = htmlspecialchars($_POST['nama']);
        $departemen = htmlspecialchars($_POST['departemen']);
        $telepon = htmlspecialchars($_POST['telepon']);
        $alamat = htmlspecialchars($_POST['alamat']);
        
        $stmt = $pdo->prepare("UPDATE manajer SET nama = :nama, departemen = :departemen, telepon = :telepon, alamat = :alamat WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':departemen', $departemen);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':alamat', $alamat);
      
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
    <link rel="stylesheet" href="manager.css"> 
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
      <li><a href="../rooms/alamat.php">alamat Management</a></li>
      <li><a href="../customers/customer.php">Customer Management</a></li>
      <li><a href="../bookings/booking.php">Booking Management</a></li>
      <li><a href="../payments/payment.php">Payment Management</a></li>
      <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Manager Management</h1>

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
  </div>


<form action="" method="POST">
    <input type="hidden" name="submit_add" value="1">
    <div class="container border border-black row" id="managerForm">
      <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add Manager</header>
      <div class="col-md-6 d-flex align-items-center">
        <label for="managerName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Name</label>
        <input name="nama" type="text" id="managerName" class="form-control flex-grow-1"><br>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <label for="departmentName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Department</label>
        <input name="departemen" type="text" id="departmentName" class="form-control flex-grow-1"><br>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <label for="managerNumber" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Phone Number</label>
        <input name="telepon" type="text" id="managerNumber" class="form-control flex-grow-1"><br>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <label for="managerAddress" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Address</label>
        <input name="alamat" type="text" id="managerAddress" class="form-control flex-grow-1"><br>
      </div>
      <div class="text-end">
      <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingManager">Save</button>
      </div>
    </div>
  </form>


  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <table id="table" class="table table-bordered">
          <thead class="table-primary">
            <tr>
              <th>ID Manager</th>
              <th>Name</th>
              <th>Department</th>
              <th>Phone Number</th>
              <th>Address</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php 
            $managers = [];
            try {
                $stmt = $pdo->query("SELECT * FROM manajer ORDER BY id");
                $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $message = "Error fetching data: " . $e->getMessage();
            }
          ?>
            <?php if(!empty($managers)) : ?>
              <?php foreach($managers as $manager): ?>
                <?php if($edit_id == $manager['id']): ?>
                    <!-- Edit Row -->
                    <tr class="edit-row">
                      <form action="" method="POST">
                        <input type="hidden" name="submit_edit" value="1">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($manager['id']); ?>">
                        <td><?php echo htmlspecialchars($manager['id']); ?></td>
                        <td><input name="nama" type="text" value="<?php echo htmlspecialchars($manager['nama']); ?>" required></td>
                        <td><input name="departemen" type="text" value="<?php echo htmlspecialchars($manager['departemen']); ?>" required></td>
                        <td><input name="telepon" type="text" value="<?php echo htmlspecialchars($manager['telepon']); ?>" required></td>
                        <td><input name="alamat" type="text" value="<?php echo htmlspecialchars($manager['alamat']); ?>" required></td>
                        <td>
                          <button type="submit" class="btn btn-success btn-sm me-1">Save</button>
                          <a href="manager.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </td>
                      </form>
                    </tr>
                  <?php else: ?>
                      <tr>
                          <td><?php echo htmlspecialchars($manager['id']); ?></td>
                          <td><?php echo htmlspecialchars($manager['nama']); ?></td>
                          <td><?php echo htmlspecialchars($manager['departemen']); ?></td>
                          <td><?php echo htmlspecialchars($manager['telepon']); ?></td>
                          <td><?php echo htmlspecialchars($manager['alamat']); ?></td>
                          <td>
                            <a href="manager.php?edit=<?php echo htmlspecialchars($manager['id']); ?>" class="btn btn-success btn-sm me-1">Edit</a>
                              <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display:inline;">
                                <input type="hidden" name="submit_delete" value="1">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($manager['id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                          </td>
                        </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No data available</td>
                </tr>
              <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>  


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
