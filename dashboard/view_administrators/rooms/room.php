<?php
  include '../../conn.php';

  // Mulai session untuk notifikasi
  session_start();

  // Inisialisasi variabel notifikasi
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Hapus notifikasi setelah ditampilkan
  unset($_SESSION['error'], $_SESSION['success']);

  // Fungsi untuk mendapatkan total kamar untuk tipe tertentu
  function getTotalRooms($pdo, $room_type) {
    try {
      $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM room WHERE room_type = :room_type");
      $stmt->bindParam(':room_type', $room_type);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    } catch (PDOException $e) {
      return 0;
    }
  }

  // Fungsi untuk mendapatkan total ketersediaan kamar
  function getRoomAvailability($pdo) {
    $room_types = [
      'Executive Suite' => 65,
      'Luxury Suite' => 25,
      'Presidential Suite' => 10
    ];

    $availability = [];
    foreach ($room_types as $type => $total) {
      $current_count = getTotalRooms($pdo, $type);
      $availability[$type] = [
        'total' => $total,
        'empty' => $total - $current_count
      ];
    }

    return $availability;
  }

  if($_SERVER['REQUEST_METHOD']==='POST'){
    if (isset($_POST['submit_add'])) {
      try{
        //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
        $room = htmlspecialchars($_POST['room']);
        $price = htmlspecialchars($_POST['price']);
        
        //prepare agar tidak terjadi SQL injection
        $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES (:room, :price)");
        $stmt->bindParam(':room', $room);
        $stmt->bindParam(':price', $price);
      
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
      $stmt = $pdo->prepare("DELETE FROM room WHERE id = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      // Eksekusi query
      $stmt->execute();

      // Pesan sukses dan tandai sebagai hapus
      $_SESSION['success'] = "Data deleted successfully!";
      $_SESSION['isDelete'] = true; // Tandai notifikasi sebagai penghapusan
  } catch (PDOException $e) {
      $_SESSION['error'] = "Error deleting data: " . $e->getMessage();
  }


      // Redirect untuk mencegah form resubmission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }

  // Get room availability
  $room_availability = getRoomAvailability($pdo);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="room.css"> 
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
      <li><a href="room.php">Room Management</a></li>
      <li><a href="../customers/customer.php">Customer Management</a></li>
      <li><a href="../bookings/booking.php">Booking Management</a></li>
      <li><a href="../payments/payment.php">Payment Management</a></li>
      <li><a href="../additionals/additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Room Management</h1>

<!-- Notifikasi -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <?php if (!empty($_SESSION['isDelete']) && $_SESSION['isDelete']): ?>
        <!-- Notifikasi sukses penghapusan data -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php else: ?>
        <!-- Notifikasi sukses umum -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Hapus tanda penghapusan setelah ditampilkan -->
<?php unset($_SESSION['isDelete']); ?>


  <div class="container">
    <div class="row">
      <!-- Table Section (Left Side) -->
      <div class="col-md-6">
        <table id="table" class="table table-bordered">
          <thead class="table-primary">
            <tr>
              <th>ID Payment</th>
              <th>Room Type</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $data = [];
              try {
                  $stmt = $pdo->query("SELECT * FROM room ORDER BY id");
                  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (Exception $e) {
                  $message = "Error fetching data: " . $e->getMessage();
              }
            ?>
            <?php if(!empty($data)) : ?>
              <?php foreach($data as $item): ?>
                      <tr>                     
                          <td><?php echo htmlspecialchars($item['id']); ?></td>
                          <td><?php echo htmlspecialchars($item['room_type']); ?></td>
                          <td><?php echo htmlspecialchars($item['price']); ?></td>
                          <td>
                          <button 
                              type="button" 
                              class="btn btn-success btn-sm editBtn"
                              data-bs-toggle="modal" 
                              data-bs-target="#editModal"
                              data-id="<?php echo htmlspecialchars($item['id']); ?>"
                              data-room="<?php echo htmlspecialchars($item['room_type']); ?>"
                              data-price="<?php echo htmlspecialchars($item['price']); ?>">
                              Edit
                            </button>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" style="display:inline;">
                              <input type="hidden" name="submit_delete" value="1">
                              <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
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

      <!-- Room Form and Availability Room Section (Right Side) -->
      <div class="col-md-6">
        <div class="row">
          <!-- Room Form -->
          <form action="" method="POST">
          <input type="hidden" name="submit_add" value="1">
          <div class="col-md-12">
            <div class="border border-black" id="roomForm">
              <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add Room</header>
              <div class="col-md-12 d-flex align-items-center">
                <label for="roomType" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Room Type</label>
                <input name="room" type="text" id="roomType" class="form-control flex-grow-1" value=""><br>
              </div>
              <div class="col-md-12 d-flex align-items-center">
                <label for="roomPrice" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Price</label>
                <input name="price" type="text" id="roomPrice" class="form-control flex-grow-1" value=""><br>
              </div>
              <button type="submit" class="btn btn-primary rounded-3 fw-bold" id="addingRooms">Save</button>
            </div>
          </div>
        </form>
        
  
          <!-- Availability Rooms -->
          <div class="col-md-9 mt-5">
            <div class="card text-white p-4 shadow" style="background-color:#1a2946;">
              <h1 class="card-title text-left mb-4">Availability Rooms</h1>
              <div class="availability-section">
              <div class="row mb-3">
                <div class="col-6 text-start">Executive Suite</div>
                <div class="col-6 text-end">
                  <div class="d-flex justify-content-end align-items-center">
                    <input type="text" class="form-control text-center me-1 availability-input" id="executive-empty" value="<?php echo $room_availability['Executive Suite']['empty']; ?>" readonly>
                    <span class="mx-1">/</span>
                    <input type="text" class="form-control text-center availability-input" id="executive-total" value="<?php echo $room_availability['Executive Suite']['total']; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-6 text-start">Luxury Suite</div>
                <div class="col-6 text-end">
                  <div class="d-flex justify-content-end align-items-center">
                    <input type="text" class="form-control text-center me-1 availability-input" id="luxury-empty" value="<?php echo $room_availability['Luxury Suite']['empty']; ?>" readonly>
                    <span class="mx-1">/</span>
                    <input type="text" class="form-control text-center availability-input" id="luxury-total" value="<?php echo $room_availability['Luxury Suite']['total']; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-6 text-start">Presidential Suite</div>
                <div class="col-6 text-end">
                  <div class="d-flex justify-content-end align-items-center">
                    <input type="text" class="form-control text-center me-1 availability-input" id="presidential-empty" value="<?php echo $room_availability['Presidential Suite']['empty']; ?>" readonly>
                    <span class="mx-1">/</span>
                    <input type="text" class="form-control text-center availability-input" id="presidential-total" value="<?php echo $room_availability['Presidential Suite']['total']; ?>" readonly>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>          
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" id="editRoomId" name="id" value="">
          <div class="mb-3">
            <label for="editRoomType" class="form-label">Room Type</label>
            <input type="text" class="form-control" id="editRoomType" name="room" required>
          </div>
          <div class="mb-3">
            <label for="editRoomPrice" class="form-label">Price</label>
            <input type="text" class="form-control" id="editRoomPrice" name="price" required>
          </div>
          <button type="button" class="btn" id="saveEditChanges" 
          style="background-color: #0d335d;
                color: white;">Save Changes</button>
        </form>
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

                    for (let j = 0; j < cells.length; j++) { 
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
