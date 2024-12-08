<?php
  include '../../conn.php';

  // Mulai session untuk notifikasi
  session_start();

  // Inisialisasi variabel notifikasi
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Hapus notifikasi setelah ditampilkan
  unset($_SESSION['error'], $_SESSION['success']);

  $edit_id = isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : null;

  if($_SERVER['REQUEST_METHOD']==='POST'){
    if (isset($_POST['submit_add'])) {
      try{
        //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
        $name = htmlspecialchars($_POST['name']);
        $status = htmlspecialchars($_POST['status']);
        $total = htmlspecialchars($_POST['total']);
        
        //prepare agar tidak terjadi SQL injection
        $stmt = $pdo->prepare("INSERT INTO payment(name, status, price) VALUES (:name, :status, :total)"); //name saja yang berubah untuk mau ganti nama kolom di supa 
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':total', $total);
      
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
          $stmt = $pdo->prepare("DELETE FROM payment  WHERE id = :id");
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
        $name = htmlspecialchars($_POST['name']);
        $status = htmlspecialchars($_POST['status']);
        $price = htmlspecialchars($_POST['price']);
        
        $stmt = $pdo->prepare("UPDATE payment SET name = :name, status = :status, price = :price WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':status', $status);
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
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="payment.css"> 
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
      <li><a href="payment.php">Payment Management</a></li>
      <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Payment Management</h1>

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


    <form action="" method="POST">
  <input type="hidden" name="submit_add" value="1">
  <div class="container border border-black row" id="paymentForm">
    <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add Payment Method</header>
    
    <!-- Dropdown untuk Metode Pembayaran -->
    <div class="col-md-6 d-flex align-items-center mb-3">
      <label for="paymentMethod" class="me-2 flex-shrink-0" style="min-width: 130px;">Payment Method</label>
      <select name="name" id="paymentMethod" class="form-control flex-grow-1">
        <option value="" selected disabled hidden>Select Payment Options </option>
        <option value="tunai">Cash</option>
        <option value="Credit Card">Credit Card</option>
        <option value="transfer">Bank Transfer</option>
      </select>
    </div>

    <!-- Dropdown untuk Status -->
    <div class="col-md-6 d-flex align-items-center mb-3">
      <label for="paymentStatus" class="me-2 flex-shrink-0" style="min-width: 130px;">Status</label>
      <select name="status" id="paymentStatus" class="form-control flex-grow-1">
        <option value="" selected disabled hidden>Select Status Options</option>
        <option value="Paid">Paid</option>
        <option value="Not yet paid">Not yet paid off</option>
      </select>
    </div>

    <div class="col-md-6 d-flex align-items-center">
      <label for="paymentDate" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Total</label>
      <input name="total" type="text" id="paymentDate" class="form-control flex-grow-1"><br>
    </div>
    <div class="col-12 d-flex justify-content-end">
    <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingPayment">Save</button>
    </div>
  </div>
</form>


  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <table id="table" class="table table-bordered">
          <thead class="table-primary">
            <tr>
              <th>ID Payment</th>
              <th>Name</th>
              <th>Status</th>
              <th>Date</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $data = [];
              try {
                  $stmt = $pdo->query("SELECT *, date_trunc('second', created_at) AS no_milli FROM payment ORDER BY id");
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
                        <td><input name="name" type="text" value="<?php echo htmlspecialchars($item['name']); ?>" required></td>
                        <td><input name="status" type="text" value="<?php echo htmlspecialchars($item['status']); ?>" required></td>
                        <td><input name="price" type="text" value="<?php echo htmlspecialchars($item['price']); ?>" required></td>
                        <td>
                          <button type="submit" class="btn btn-success btn-sm me-1">Save</button>
                          <a href="payment.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </td>
                      </form>
                    </tr>
                  <?php else: ?>
                      <tr>
                          <td><?php echo htmlspecialchars($item['id']); ?></td>
                          <td><?php echo htmlspecialchars($item['name']); ?></td>
                          <td><?php echo htmlspecialchars($item['status']); ?></td>
                          <td><?php echo htmlspecialchars($item['no_milli']); ?></td>
                          <td><?php echo htmlspecialchars($item['price']); ?></td>
                          <td>
                            <a href="payment.php?edit=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-success btn-sm me-1">Edit</a>
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
