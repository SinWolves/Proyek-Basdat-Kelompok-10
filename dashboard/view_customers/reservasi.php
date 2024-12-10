<?php
  include '../conn.php';

  // Mulai session untuk notifikasi
  session_start();

  // Inisialisasi variabel notifikasi
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Untuk ke constomer
  unset($_SESSION['error'], $_SESSION['success']);
  
  if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama = $_SESSION['nama'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $telepon = $_SESSION['phone'];
    $tanggal_lahir = null;
    $stmt = $pdo->prepare("INSERT INTO customer (nama, email, username, telepon, tanggal_lahir ) VALUES (:name, :email, :username, :telepon, :tanggal_lahir )");
    $stmt->bindParam(':name', $nama);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':telepon', $telepon);
    $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
    $stmt->execute();
}

    if($_SERVER['REQUEST_METHOD']==='POST'){
        if (isset($_POST['submit_add'])) {
            //memasukkan data ke 4 tabel dengan query terpisah

            //total keseluruhan nantinya (mulai dari 0 dulu)
            $universal_total_price = 0;
            try {
                $nama_InRoomDining = "Personalized In-Room Dining";
                $nama_InRoomSpa = "24/7 In-Room Spa Services";
                $nama_Fitness = "Personal Fitness Trainer & Wellness Coach";
                $nama_KidsClub = "Exclusive Kids Club with Personalized Activities";
            
                //nilai
                $valueInRoomDining = (int) htmlspecialchars($_POST['in_room_dining']); 
                $valueInRoomSpa = (int) htmlspecialchars($_POST['in_room_spa']);
                $valueFitness = (int) htmlspecialchars($_POST['fitness']);  
                $valueKidsClub = (int) htmlspecialchars($_POST['kids_club']);
            
                //description
                $descriptionInRoomDining = "$nama_InRoomDining selama $valueInRoomDining session";
                $descriptionInRoomSpa = "$nama_InRoomSpa selama $valueInRoomSpa session";
                $descriptionFitness = "$nama_Fitness selama $valueFitness jam";
                $descriptionKidsClub = "$nama_KidsClub selama $valueKidsClub child";
            
                // harga
                $priceInRoomDining = 500000.00;  
                $priceInRoomSpa = 1000000.00;  
                $priceFitness = 800000.00; 
                $priceKidsClub = 400000.00;  
            
                //total
                $totalPriceInRoomDining = $priceInRoomDining * $valueInRoomDining;
                $totalPriceInRoomSpa = $priceInRoomSpa * $valueInRoomSpa;
                $totalPriceFitness = $priceFitness * $valueFitness;
                $totalPriceKidsClub = $priceKidsClub * $valueKidsClub;

                $total_addition = $totalPriceInRoomDining + $totalPriceInRoomSpa + $totalPriceFitness + $totalPriceKidsClub;
                // universal total dari addition
                $universal_total_price += $total_addition;
     
                if ($valueInRoomDining > 0) {
                    $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
                    $stmt->bindParam(':name', $nama_InRoomDining);
                    $stmt->bindParam(':description', $descriptionInRoomDining);
                    $stmt->bindParam(':price', $totalPriceInRoomDining);
                    $stmt->execute();
                }
            
                if ($valueInRoomSpa > 0) {
                    $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
                    $stmt->bindParam(':name', $nama_InRoomSpa);
                    $stmt->bindParam(':description', $descriptionInRoomSpa);
                    $stmt->bindParam(':price', $totalPriceInRoomSpa);
                    $stmt->execute();
                }
            
                if ($valueFitness > 0) {
                    $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
                    $stmt->bindParam(':name', $nama_Fitness);
                    $stmt->bindParam(':description', $descriptionFitness);
                    $stmt->bindParam(':price', $totalPriceFitness);
                    $stmt->execute();
                }
            
                if ($valueKidsClub > 0) {
                    $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
                    $stmt->bindParam(':name', $nama_KidsClub);
                    $stmt->bindParam(':description', $descriptionKidsClub);
                    $stmt->bindParam(':price', $totalPriceKidsClub);
                    $stmt->execute();
                }
            }catch (PDOException $e) {
                $_SESSION['error'] = "Error adding data: " . $e->getMessage();
            }     
            
            try {
                // Room: Executive Suite
                if ($_POST['executive-quantity'] > 0) { 
                    for ($i = 0; $i < $_POST['executive-quantity']; $i++) {
                        $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES ('Executive Suite', :price)");
                        $stmt->bindValue(':price', 3500000.00);
                        $stmt->execute();
                    }
            
                    $id_customer = 2;  
                    $check_in = htmlspecialchars($_POST['check_in']);
                    $check_out = htmlspecialchars($_POST['check_out']);
                    $total_price = 3500000.00 * $_POST['executive-quantity'];
            
                    $universal_total_price += $total_price;

                    if ($total_price <= 0) {
                        $_SESSION['error'] = "Error adding service: Executive room price calculation failed.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit(); 
                    }
            
                    $stmt = $pdo->prepare("INSERT INTO booking(id_customer, check_in, check_out, room, price, status_pemesanan) VALUES (:id_customer, :check_in, :check_out, 'Executive', :total_price, 'Dipesan')");
                    $stmt->bindParam(':id_customer', $id_customer);
                    $stmt->bindParam(':check_in', $check_in);
                    $stmt->bindParam(':check_out', $check_out);
                    $stmt->bindParam(':total_price', $total_price);
                    $stmt->execute();
                }
            
                // Room: Luxury Suite
                if ($_POST['luxury-quantity'] > 0) {
                    for ($i = 0; $i < $_POST['luxury-quantity']; $i++) {
                        $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES ('Luxury Suite', :price)");
                        $stmt->bindValue(':price', 7500000.00);
                        $stmt->execute();
                    }
            
                    $id_customer = 2;  
                    $check_in = htmlspecialchars($_POST['check_in']);
                    $check_out = htmlspecialchars($_POST['check_out']);
                    $total_price = 7500000.00 * $_POST['luxury-quantity'];

                    $universal_total_price += $total_price;
            
                    if ($total_price <= 0) {
                        $_SESSION['error'] = "Error adding service: Luxury room price calculation failed.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit(); 
                    }
            
                    $stmt = $pdo->prepare("INSERT INTO booking(id_customer, check_in, check_out, room, price) VALUES (:id_customer, :check_in, :check_out, 'Luxury', :total_price)");
                    $stmt->bindParam(':id_customer', $id_customer);
                    $stmt->bindParam(':check_in', $check_in);
                    $stmt->bindParam(':check_out', $check_out);
                    $stmt->bindParam(':total_price', $total_price);
                    $stmt->execute();
                }
            
                // Room: Presidential Suite
                if ($_POST['presidential-quantity'] > 0) {
                    for ($i = 0; $i < $_POST['presidential-quantity']; $i++) {
                        $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES ('Presidential Suite', :price)");
                        $stmt->bindValue(':price', 15000000.00);
                        $stmt->execute();
                    }
            
                    $id_customer = 2;  // Assuming this is the current customer ID
                    $check_in = htmlspecialchars($_POST['check_in']);
                    $check_out = htmlspecialchars($_POST['check_out']);
                    $total_price = 15000000.00 * $_POST['presidential-quantity'];

                    $universal_total_price += $total_price;
            
                    // Check if total price is valid
                    if ($total_price <= 0) {
                        $_SESSION['error'] = "Error adding service: Presidential room price calculation failed.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit(); 
                    }
            
                    $stmt = $pdo->prepare("INSERT INTO booking(id_customer, check_in, check_out, room, price) VALUES (:id_customer, :check_in, :check_out, 'Presidential', :total_price)");
                    $stmt->bindParam(':id_customer', $id_customer);
                    $stmt->bindParam(':check_in', $check_in);
                    $stmt->bindParam(':check_out', $check_out);
                    $stmt->bindParam(':total_price', $total_price);
                    $stmt->execute();
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Error adding data: " . $e->getMessage();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        

            try{
                //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection     
                if (empty($_POST['paymentMethod'])) {
                    $_SESSION['error'] = "Please select a payment method.";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
                $name = htmlspecialchars($_POST['paymentMethod']);
                $status = "PAID";
                
                //prepare agar tidak terjadi SQL injection
                $stmt = $pdo->prepare("INSERT INTO payment(name, status, price) VALUES (:name, :status, :price)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':price', $universal_total_price);
              
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
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family:'Luxurious Roman', cursive;
        }

        .navbar {
            background-color: #15274b;
            font-family: 'Luxurious Roman';
            margin-bottom: 0; 
            position: sticky;
            top: 0;
        }

        .navbar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        .navbar .btn {
            font-family: 'Luxurious Roman', sans-serif ;
        }
        
        .navbar .btn-outline-light {
            border-color: white;
            color: white;
        }
        
        .navbar .btn-outline-light:hover {
            background-color: white;
            color: black;
        }

        .reservation-header {
            font-family: 'Luxurious Roman';
            font-weight: bold;
            margin-top: 30px;
            text-align: left;
            padding: 0 20px;
            font-size: 2rem;
            color: #001f54;
        }

        .reservation-form {
            padding: 20px;
        }

        .payment{
            border-radius: 100px;
        }

        .card-header {
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-body{
            font-size: 1.2rem;
            color: white;
        }

        .btn-primary {
            color: #15274b;
            background-color: #eae2be;
            border: none;
        }

        .btn-primary:hover {
            background-color: #eae2be;
        }

        .text-highlight {
            color: white;
            font-weight: bold;
        }

        .increment-buttons input {
            width: 60px;
            text-align: center;
        }

        .additional-service-item span.service-name {
        font-size: 1rem;
        color: black;
        font-family: 'Luxurious Roman', serif;
    }

        .additional-service-item span.service-price {
            font-size: 0.9rem;
            color: #6c757d; 
            font-family: 'Arial', sans-serif;
            display: block; 
            margin-top: 5px;
        }

        .btn-book-now {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 0.9rem; 
            padding: 5px 15px; 
            background-color: #eae2be; 
            color: #15274b; 
            border: none;
            border-radius: 5px; 
        }

        .btn-book-now:hover {
            background-color: #d4c69d; 
        }

        .card.mb-4 {
            height: 80%
        }

        

            </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
        <a class="navbar-brand" href="index.html">MOONLIT HOTEL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php">Rooms & Suites</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php">Facilities</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php">Additional Services</a></li>
            <li class="nav-item"><a class="nav-link" href="reservasi.php">Booking</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php">Contact Us</a></li>
            </ul>
            <div class="d-flex ms-3">
            <button class="btn btn-outline-light me-2"  onclick="window.location.href='login.php';">Login</button>
            <button class="btn btn-primary" style="background-color: black; color: white;" onclick="window.location.href='signup.html';">Sign up</button>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <img id="profile-account" alt="pp user" class="rounded-circle" height="30" src="img/f10ff70a7155e5ab666bcdd1b45b726d.jpg" width="30"/>
                </div>
                <span style="color: white;" class="ms-3"><?= $_SESSION['nama']; ?></span>
        </div>
        </div>
    </nav>

    <div class="reservation-header ">Reservation</div>

    <form action="" method="POST">
    <input type="hidden" name="submit_add" value="1">
    <div class="reservation-form container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="checkIn" class="form-label">Check-In</label>
                    <input name="check_in" type="date" class="form-control" id="checkIn" required>
                </div>
                <div class="mb-3">
                    <label for="checkOut" class="form-label">Check-Out</label>
                    <input name="check_out" type="date" class="form-control" id="checkOut" required>
                </div>
                <div class="card">
                    <div class="card-header" style="background-color: #001f54; color: #eae2be; font-family: 'Luxurious Roman';">Rooms & Suites</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-family: 'Luxurious Roman';" >Executive Suite</span>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="executive-quantity" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-family: 'Luxurious Roman';" >Luxury Suite</span>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="luxury-quantity" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-family: 'Luxurious Roman';" >Presidential Suite</span>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="presidential-quantity" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #001f54; font-family: 'Luxurious Roman';">Total</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <span class="text-highlight" style="font-family: 'Luxurious Roman';">IDR 0</span>
                    </div>
                    <div class="card-header" style="background-color: #001f54;">Payment Method</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="Cash">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="cash">Cash Payment</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="Credit card">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="creditCard">Credit Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer" value="Bank transfer">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="bankTransfer">Bank Transfer</label>
                        </div>
                        <button name="book_now" type="submit" class="btn btn-book-now">Book Now</button>
                    </div>
                </div>
            </div>

        <div class="card mt-4" id="addition">
            <div class="card-header" style="background-color:white; color:#001f54; font-family: 'Luxurious Roman';">Additional Services</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Personalized In-Room Dining</span>
                                <span class="service-price">IDR 500,000/session</span>
                            </div>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="in_room_dining" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">24/7 In-Room Spa Services</span>
                                <span class="service-price">IDR 1,000,000/session</span>
                            </div>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="in_room_spa" type="text" value="0" readonly>
                                <button type="button" type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Personal Fitness Trainer & Wellness Coach</span>
                                <span class="service-price">IDR 800,000/hour</span>
                            </div>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="fitness" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Exclusive Kids Club with Personalized Activities</span>
                                <span class="service-price">IDR 400,000/day/child</span>
                            </div>
                            <div class="increment-buttons">
                                <button type="button" class="btn btn-sm btn-light">-</button>
                                <input name="kids_club" type="text" value="0" readonly>
                                <button type="button" class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // dynamic totalprice di ui
    function updateTotalPrice() {
        // Room prices
        const roomPrices = {
            executive: 3500000,
            luxury: 7500000,
            presidential: 15000000
        };

        // Additional service prices
        const servicePrices = {
            in_room_dining: 500000,
            in_room_spa: 1000000,
            fitness: 800000,
            kids_club: 400000
        };

        let executiveQuantity = parseInt(document.querySelector('[name="executive-quantity"]').value) || 0;
        let luxuryQuantity = parseInt(document.querySelector('[name="luxury-quantity"]').value) || 0;
        let presidentialQuantity = parseInt(document.querySelector('[name="presidential-quantity"]').value) || 0;

        // Get values from the inputs for additional services
        let inRoomDining = parseInt(document.querySelector('[name="in_room_dining"]').value) || 0;
        let inRoomSpa = parseInt(document.querySelector('[name="in_room_spa"]').value) || 0;
        let fitness = parseInt(document.querySelector('[name="fitness"]').value) || 0;
        let kidsClub = parseInt(document.querySelector('[name="kids_club"]').value) || 0;

        // room
        let totalRoomPrice = (executiveQuantity * roomPrices.executive) +
                             (luxuryQuantity * roomPrices.luxury) +
                             (presidentialQuantity * roomPrices.presidential);

        // servis
        let totalServicePrice = (inRoomDining * servicePrices.in_room_dining) +
                                (inRoomSpa * servicePrices.in_room_spa) +
                                (fitness * servicePrices.fitness) +
                                (kidsClub * servicePrices.kids_club);

        // total
        let totalPrice = totalRoomPrice + totalServicePrice;

        // Update total di ui
        document.querySelector('.reservation-form .payment .card-body .text-highlight').textContent = `IDR ${totalPrice.toLocaleString()}`;
        document.getElementById('totalPriceInput').value = totalPrice;
    }

    // Add event listeners to all quantity input fields
    document.querySelectorAll('.increment-buttons button').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            let currentValue = parseInt(input.value) || 0;

            // Increment or decrement based on button clicked
            if (this.textContent === '+') {
                input.value = currentValue + 1;
            } else if (this.textContent === '-') {
                input.value = currentValue > 0 ? currentValue - 1 : 0;
            }

            // Update the total price
            updateTotalPrice();
        });
    });

    // Initialize total price on page load
    updateTotalPrice();
</script>
    
</body>
</html>