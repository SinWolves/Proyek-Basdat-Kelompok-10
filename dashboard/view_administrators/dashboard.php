<?php
  require_once '../conn_local.php';

  // Query untuk mengambil data dari database
$query = "SELECT * FROM manajer";
$result = mysqli_query($conn, $query);

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
    <style>
      /* Dashboard container */
      .container {
        margin-top: 20px;
      }

      .section-title {
        font-size: 20px;
        color: #000000;
      }
        .form-control {
        border: 1px solid #0d335d;
        margin-bottom: 10px;
      }


      /* Table*/
      
      .table-bordered th,
      .table-bordered td {
        border: 1px solid #000000;
      }

      /* Ubah warna header tabel */
      .table-primary th {
      background-color: #2C5099;
      color: #ffffff;
      text-align: center;
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
      <button class="logout">Logout</button>
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
      <h3>Hotel</h3>
      <div class="row mb-4">
        <!-- Hotel and Address Section -->
        <div class="col-md-6 d-flex align-items-center">
          <label for="hotelName" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Hotel</label>
          <input type="text" id="hotelName" class="form-control flex-grow-1" value="Moonlit Hotel" readonly>
          <button class="btn btn-outline-primary ms-1 mb-2" id="editHotelBtn">Edit</button>
        </div>

        <div class="col-md-6 d-flex align-items-center">
          <label for="hotelAddress" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Address</label>
          <input type="text" id="hotelAddress" class="form-control flex-grow-1" value="Jakarta" readonly>
          <button class="btn btn-outline-primary ms-1 mb-2" id="editAddressBtn">Edit</button>
        </div>
      </div>
      
      <div class="row mb-4">
        <!-- Total Bookings and Total Customers Section -->
        <div class="col-md-6 d-flex align-items-center">
          <label for="totalBookings" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Total Bookings</label>
          <input type="text" id="totalBookings" class="form-control flex-grow-1">
        </div>
        <div class="col-md-6 d-flex align-items-center">
          <label for="totalCustomers" class="section-title me-2 flex-shrink-0" style="min-width: 130px;">Total Customers</label>
          <input type="text" id="totalCustomers" class="form-control flex-grow-1">
        </div>
      </div>
      
      <h3>Overview</h3>
      <!-- Monthly Revenue and Review Section -->
      <div class="row">
        <!-- Monthly Revenue -->
        <div class="col-md-6">
            <h3 style="text-align: center; font-size:20px ; color:#000000">Monthly Revenue</h3>
              <table class="table table-bordered">
                <thead class="table-primary">
                  <tr>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Revenue (IDR)</th>
                    <th>Transactions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>January</td>
                    <td>2024</td>
                    <td>10,000,000</td>
                    <td>15</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="text-center">No more data available</td>
                  </tr>
                </tbody>
              </table>
        </div>

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
              <tr>
                <td>JohnDoe</td>
                <td>Excellent service!</td>
                <td>17/11/2024</td>
                <td><button class="btn btn-danger btn-sm">Delete</button></td>
              </tr>
              <tr>
                <td colspan="4" class="text-center">No more reviews</td>
              </tr>
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
