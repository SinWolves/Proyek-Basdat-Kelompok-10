<?php
  include '../../conn.php';

  // Start the session for notifications
  session_start();

  // Initialize notification variables
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Clear notifications after displaying
  unset($_SESSION['error'], $_SESSION['success']);

     // Variabel untuk edit mode
     $edit_id = isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_add'])) {
      try {
        // Sanitize and retrieve form inputs
        $idStaff = htmlspecialchars($_POST['idStaff']);
        $gaji = htmlspecialchars($_POST['gaji']);
        $status = htmlspecialchars($_POST['status']);

        // Check if the ID exists in the staff table
        $stmt = $pdo->prepare("SELECT nama FROM staff WHERE id = :idStaff");
        $stmt->bindParam(':idStaff', $idStaff, PDO::PARAM_INT);
        $stmt->execute();
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff) {
          // If staff exists, get the name
          $staffName = $staff['nama'];

          // Prepare the query to insert the salary data
          $stmt = $pdo->prepare("INSERT INTO salary(staff_id, nama_staf, gaji, status_gaji) VALUES (:idstaf, :name, :gaji, :status)");
          $stmt->bindParam(':idstaf', $idStaff);
          $stmt->bindParam(':name', $staffName);
          $stmt->bindParam(':gaji', $gaji);
          $stmt->bindParam(':status', $status);

          // Execute the insert query
          $stmt->execute();

          // Success message
          $_SESSION['success'] = "New salary record added successfully!";
        } else {
          // If staff doesn't exist, show error message
          $_SESSION['error'] = "Staff ID not found!";
        }

        // Prevent form resubmission on page refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 

      } catch (PDOException $e) {
        // Handle SQL errors
        $_SESSION['error'] = "Error adding data: " . $e->getMessage();
      }
    }

    // Delete data based on ID
    if (isset($_POST['submit_delete'])) {
      $id = htmlspecialchars($_POST['id']);

      try {
        // Prepare statement for deletion
        $stmt = $pdo->prepare("DELETE FROM salary WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Success message
        $_SESSION['success'] = "Data deleted successfully!";
      } catch (PDOException $e) {
        // Handle errors
        $_SESSION['error'] = "Error deleting data: " . $e->getMessage();
      }

      // Redirect to avoid resubmission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } 

    // Proses update data
    if (isset($_POST['submit_edit'])) {
      try {
        $id = htmlspecialchars($_POST['id']);
        $nama_staf = htmlspecialchars($_POST['nama_staf']);
        $gaji = htmlspecialchars($_POST['gaji']);
        $status_gaji = htmlspecialchars($_POST['status_gaji']);
        
        $stmt = $pdo->prepare("UPDATE salary SET nama_staf = :nama_staf, gaji = :gaji, status_gaji = :status_gaji WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_staf', $nama_staf);
        $stmt->bindParam(':gaji', $gaji);
        $stmt->bindParam(':status_gaji', $status_gaji);
      
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
    <link rel="stylesheet" href="salary.css"> 
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
      <li><a href="../customers/customer.php">Customer Management</a></li>
      <li><a href="../bookings/booking.php">Booking Management</a></li>
      <li><a href="../payments/payment.php">Payment Management</a></li>
      <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Staff Salary Management</h1>

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

<div class="col-md-6">
        <form action="" method="POST">
        <input type="hidden" name="submit_add" value="1">
        <div class="border border-black p-3" id="serviceForm">
          <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add New Salary</header>
          <!-- ID Customer -->
          <div class="d-flex align-items-center mb-3">
            <label for="idcustomer" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Staff ID</label>
            <input name="idStaff" type="number" id="idcustomer" class="form-control flex-grow-1" value="">
          </div>
          <!-- Check-In -->
          <div class="d-flex align-items-center mb-3">
            <label for="checkIncheck_out" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Salary Amount</label>
            <input name="gaji" type="status_gaji" id="status_gaji" class="form-control flex-grow-1">
          </div>
          <div class="d-flex align-items-center mb-3">
            <label for="checkOutcheck_out" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Status</label>
            <input name="status" type="telepon" id="status_gaji" class="form-control flex-grow-1">
          </div>

          
          <!-- Submit Button -->
          <div class="text-end">
            <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingService">Save</button>
          </div>
        </div>
        </form>
        
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <table id="table" class="table table-bordered">
          <thead class="table-primary">
            <tr>
              <th>ID Salary</th>
              <th>Staff Name</th>
              <th>Salary Amount</th>
              <th>Year</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php 
              $data = [];
              try {
                  $stmt = $pdo->query("SELECT *, date_trunc('second', tahun) as no_milli FROM salary ORDER BY id");
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
                        <td><input name="nama_staf" type="text" value="<?php echo htmlspecialchars($item['nama_staf']); ?>" required></td>
                        <td><input name="gaji" type="text" value="<?php echo htmlspecialchars($item['gaji']); ?>" required></td>
                        <td><input name="gaji" type="text" value="<?php echo htmlspecialchars($item['no_milli']); ?>" readonly></td>
                        <td><input name="status_gaji" type="text" value="<?php echo htmlspecialchars($item['status_gaji']); ?>" required></td>
                        <td>
                          <button type="submit" class="btn btn-success btn-sm me-1">Save</button>
                          <a href="staffsalary.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </td>
                      </form>
                    </tr>
                  <?php else: ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['id']); ?></td>
                  <td><?php echo htmlspecialchars($item['nama_staf']); ?></td>
                  <td><?php echo htmlspecialchars($item['gaji']); ?></td>
                  <td><?php echo htmlspecialchars($item['no_milli']); ?></td>
                  <td><?php echo htmlspecialchars($item['status_gaji']); ?></td>
                  <td>
                    <a href="staffsalary.php?edit=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-success btn-sm me-1">Edit</a>
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
                <td colspan="6" class="text-center">No data available</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

        </div>
        </form>

      </div>
    </div>
  </div> 

    <script src="../../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
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
