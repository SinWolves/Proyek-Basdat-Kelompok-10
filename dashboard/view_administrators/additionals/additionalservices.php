<?php
  include '../../conn.php';

  if($_SERVER['REQUEST_METHOD']==='POST'){
  
    //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    
    //prepare agar tidak terjadi SQL injection
    $stmt = $pdo->prepare("INSERT INTO additional_service(name, description, price) VALUES (:name, :description, :price)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
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
<b>
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
      <li><a href="additionalservices.php">Additional Services Management</a></li>
      <li><a href="../staffs/staff.php">Staff Management</a></li>
      <li><a href="../salarys/staffsalary.php">Staff Salary Management</a></li>
      <li><a href="../managers/manager.php">Manager Management</a></li>
    </ul>
  </div>

  <div class="container">
    <h1 class="mb-4">Additional Services Management</h1>
  </div>

  <!--table-->
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <table class="table table-bordered">
          <thead class="table-primary">
            <tr>
              <th>ID Add Service</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $data = [];
              try {
                  $stmt = $pdo->query("SELECT * FROM additional_service ORDER BY id");
                  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (Exception $e) {
                  $message = "Error fetching data: " . $e->getMessage();
              }
            ?>
            <?php if(!empty($data)) : ?>
              <?php foreach($data as $item): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($item['id']); ?></td>
                          <td><?php echo htmlspecialchars($item['name']); ?></td>
                          <td><?php echo htmlspecialchars($item['description']); ?></td>
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
    </div>
  </div>  


  <form action="" method="POST">
  <div class="container border border-black row" id="serviceForm">
    <header class="mb-4 text-start fw-bold fs-5 pt-3" style="color: #2c5099;">Add New Service</header>
    <!--service name-->
    <div class="col-md-6 d-flex align-items-center">
      <label for="serviceName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Service Name</label>
      <input name="name" type="text" id="serviceName" class="form-control flex-grow-1" value=""><br>
    </div>
    <!--description-->
    <div class="col-md-6 d-flex align-items-center">
      <label for="serviceDescription" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Description</label>
      <input name="description" type="text" id="serviceDescription" class="form-control flex-grow-1" value=""><br>
    </div>
    <!--price-->
    <div class="col-md-6 d-flex align-items-center">
      <label for="servicePrice" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Price</label>
      <input name="price" type="text" id="servicePrice" class="form-control flex-grow-1" value=""><br>
    </div>
    <!--submit button-->
    <div class="col-md-12 text-end">
    <button type="submit" class="btn btn-primary rounded-3 fw-bold " id="addingService">Save</button>
    </div>
  </div>
</form>



  
    <script src="../../sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>
