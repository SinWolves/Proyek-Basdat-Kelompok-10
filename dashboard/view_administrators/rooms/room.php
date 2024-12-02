<?php
  include '../../conn.php';

  if($_SERVER['REQUEST_METHOD']==='POST'){
  
    //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
    $room = htmlspecialchars($_POST['room']);
    $price = htmlspecialchars($_POST['price']);
    
    //prepare agar tidak terjadi SQL injection
    $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES (:room, :price)");
    $stmt->bindParam(':room', $room);
    $stmt->bindParam(':price', $price);
  
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
    <link rel="stylesheet" href="room.css"> 
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
  </div>

  <div class="container">
    <div class="row">
      <!-- Table Section (Left Side) -->
      <div class="col-md-6">
        <table class="table table-bordered">
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
  
      <!-- Room Form and Availability Room Section (Right Side) -->
      <div class="col-md-6">
        <div class="row">
          <!-- Room Form -->
          <form action="" method="POST">
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
              <div class="availability-section"> <!-- Add this class for left alignment -->
                <div class="row mb-3">
                  <div class="col-6 text-start">Executive Suite</div>
                  <div class="col-6 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                      <input type="text" class="form-control text-center me-1 availability-input" id="executive-empty" value="10" readonly>
                      <span class="mx-1">/</span>
                      <input type="text" class="form-control text-center availability-input" id="executive-total" value="65" readonly>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-6 text-start">Luxury Suite</div>
                  <div class="col-6 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                      <input type="text" class="form-control text-center me-1 availability-input" id="luxury-empty" value="5" readonly>
                      <span class="mx-1">/</span>
                      <input type="text" class="form-control text-center availability-input" id="luxury-total" value="25" readonly>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-6 text-start">Presidential Suite</div>
                  <div class="col-6 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                      <input type="text" class="form-control text-center me-1 availability-input" id="presidential-empty" value="2" readonly>
                      <span class="mx-1">/</span>
                      <input type="text" class="form-control text-center availability-input" id="presidential-total" value="10" readonly>
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
  
    <script src="../../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>
