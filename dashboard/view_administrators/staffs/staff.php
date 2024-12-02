<?php
  include '../../conn.php';

  if($_SERVER['REQUEST_METHOD']==='POST'){
  
    //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
    $nama = htmlspecialchars($_POST['nama']);
    $departemen = htmlspecialchars($_POST['departemen']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $alamat = htmlspecialchars($_POST['alamat']);
    
    //prepare agar tidak terjadi SQL injection
    $stmt = $pdo->prepare("INSERT INTO staff(nama, departemen, telepon, alamat) VALUES (:nama, :departemen, :telepon, :alamat)");
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':departemen', $departemen);
    $stmt->bindParam(':telepon', $telepon);
    $stmt->bindParam(':alamat', $alamat);
  
    //jalankan kode
    $stmt->execute();

    //agar submit tidak diulangi ketika web di refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); 
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
      <li><a href="staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Staff Management</h1>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-md-6">
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
          <?php 
            $staffs = [];
            try {
                $stmt = $pdo->query("SELECT * FROM staff ORDER BY id");
                $staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $message = "Error fetching data: " . $e->getMessage();
            }
          ?>
            <?php if(!empty($staffs)) : ?>
              <?php foreach($staffs as $staff): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($staff['id']); ?></td>
                          <td><?php echo htmlspecialchars($staff['nama']); ?></td>
                          <td><?php echo htmlspecialchars($staff['departemen']); ?></td>
                          <td><?php echo htmlspecialchars($staff['telepon']); ?></td>
                          <td><?php echo htmlspecialchars($staff['alamat']); ?></td>
                          <td>
                              <button class="btn btn-danger btn-sm">Delete</button>
                          </td>
                      </tr>
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

  <form action="" method="POST">
  <div class="container border border-black row" id="staffForm">
    <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add Staff</header> 
    <div class="col-md-6 d-flex align-items-center">
      <label for="staffName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Name</label>
      <input name="nama" type="text" id="staffName" class="form-control flex-grow-1" value=""><br>
    </div>
    <div class="col-md-6 d-flex align-items-center">
      <label for="departmentName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Department</label>
      <input name="departemen" type="text" id="departmentName" class="form-control flex-grow-1" value=""><br>
    </div>
    <div class="col-md-6 d-flex align-items-center">
      <label for="staffNumber" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Phone Number</label>
      <input name="telepon" type="text" id="staffNumber" class="form-control flex-grow-1" value=""><br>
    </div>
    <div class="col-md-6 d-flex align-items-center">
      <label for="staffAddress" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Address</label>
      <input name="alamat" type="text" id="staffAddress" class="form-control flex-grow-1" value=""><br>
    </div>
    <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingStaff">Save</button>
  </div>
</form>

    <script src="../../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>