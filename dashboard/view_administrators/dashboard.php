<?php 
  include '../conn.php';

  $stmt = $pdo->query("SELECT COUNT(*) AS jmlh_booking FROM booking");
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $booking = $result['jmlh_booking'];

  $stmt = $pdo->query("SELECT COUNT(*) AS jmlh_customer FROM customer");
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $customer = $result['jmlh_customer'];

  // Menghapus data berdasarkan ID
  if (isset($_POST['submit_delete'])) {
    $id = htmlspecialchars($_POST['id']);

    try {
        // Prepare statement untuk menghapus data
        $stmt = $pdo->prepare("DELETE FROM review WHERE id = :id");
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
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="admin.css"> 
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
      <a href="../view_customers/login.php" class="logout">Logout</a>
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
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="rooms/room.php">Room Management</a></li>
      <li><a href="customers/customer.php">Customer Management</a></li>
      <li><a href="bookings/booking.php">Booking Management</a></li>
      <li><a href="payments/payment.php">Payment Management</a></li>
      <li><a href="additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="staffs/staff.php">Staff Management</a></li>
      <li><a href="salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

    <!-- Dashboard Container -->
    <div class="container">
      <h1 class="mb-4">Dashboard</h1>
  

      <div class="row mb-4 text-center">
        <!-- Hotel and Address Section -->
        <h3>Hotel MoonLit </h3>
        <h6> Sunrise Street 14, Tulung Agung
Kediri, West Java
12291</h6>
      </div>
      
      <div class="row mb-5">
        <!-- Total Bookings -->
        <div class="col-md-6 d-flex align-items-center">
          <label for="totalBookings" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Total Bookings</label>
          <p id="totalBookings" class="form-control flex-grow-1 " style="border: 1px solid  #0d335d;; padding: 10px;"><?php echo $booking; ?></p>
        </div>

        <!-- Total Customers -->
        <div class="col-md-6  d-flex align-items-center">
          <label for="totalCustomers" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Total Customers</label>
          <p id="totalCustomers" class="form-control flex-grow-1 " style="border: 1px solid  #0d335d;; padding: 10px;"><?php echo $customer; ?></p>
        </div>
      </div>
      
      <h3>Overview</h3>
      <!-- Monthly Revenue and Review Section -->
        <div class="col-md-6">
          <h3 style="text-align: center; font-size:20px; color:#000000;">Review</h3>
          <table class="table table-bordered">
            <thead class="table-primary">
              <tr>
                <th>Username</th>
                <th>Review</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $data = [];
                try {
                    $stmt = $pdo->query("SELECT review.*, akun.id AS akun_id, akun.username AS username, date_trunc('second', created_at) AS no_milli FROM review
                                          JOIN akun
                                          ON review.id_akun = akun.id
                                         ORDER BY id");
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $message = "Error fetching data: " . $e->getMessage();
                }
              ?>
              <?php if(!empty($data)) : ?>
                <?php foreach($data as $item): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($item['username']); ?></td>
                      <td><?php echo htmlspecialchars($item['review']); ?></td>
                      <td><?php echo htmlspecialchars($item['no_milli']); ?></td>
                      <td>
                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display:inline;">
                          <input type="hidden" name="submit_delete" value="1">
                          <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                      </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="7" class="text-center">No data available</td>
                  </tr>
                <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      </div>
    </div>



    </div>
    <script src="admin.js"></script>
    <script src="../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>
