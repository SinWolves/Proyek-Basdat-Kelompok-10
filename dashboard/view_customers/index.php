<? php 
session_start(); 

// akan dilakukan pemeriksaan apakah ada session user yang aktif, jika tidak arahkan ke login.php 
if(!isset($_SESSION['akun'])) {
header('location: login.php'); 
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Moonlit Hotel</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="styles.css">
        <style>
        .modal-footer .btn {
            background-color: #15274b;
            border: none;
        }

        .modal-footer .btn:hover {
            background-color: #2c5099;
        }

        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
            <a class="navbar-brand" href="index.html">MOONLIT HOTEL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#rooms">Rooms & Suites</a></li>
                <li class="nav-item"><a class="nav-link" href="#facilities">Facilities</a></li>
                <li class="nav-item"><a class="nav-link" href="#additionalservices">Additional Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#contactus">Contact Us</a></li>
                </ul>
                <div class="d-flex ms-3">
                    <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="signup.php" class="btn btn-primary" style="background-color: rgb(182, 182, 182);">Sign up</a>
                </div>
                
            </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="hero" id="hero">
            <img src="../view_customers/img/landingpage.png" alt="Home">
        </section>

        <section id="about">
            <header class="title">
                <h1><span style="color: #2c5099;">About</span> Us</h1>
            </header>

            <div class="container1">
                <div class="roomPicture">
                    <img src="" alt="">
                </div>
                <div class="explain">
                    <p>Welcome to Moonlit Hotel, where luxury meets comfort in the heart of Indonesia. 
                        Our hotel offers a seamless blend of modern amenities and timeless elegance, 
                        making it the perfect destination for both business and leisure travelers. 
                        Each room is thoughtfully designed to provide a relaxing escape, complemented by our world-class facilities and personalized service. 
                        Our dedicated team is committed to ensuring every stay is memorable, whether you're here for a quick getaway or an extended visit. 
                        Experience unparalleled hospitality and let us make your stay truly extraordinary at Moonlit Hotel.
                    </p>
                </div>
            </div>            
        </section>

        <section class="rooms" id="rooms">
            <header class="caption">
                <h1>Rooms & Suites</h1>
            </header>

            <div class="container2">
                <!--executive-->
                <div class="container2">
                    <div class="card" style="width: 18rem;">
                        <img src="../view_customers/img/executive.png" class="card-img-top" alt="Executive Suite">
                        <div class="card-body">
                            <h5 class="card-title">Executive Suite</h5>
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" style="background-color: #15274b;" data-bs-toggle="modal" data-bs-target="#executiveModal">View More</button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="executiveModal" tabindex="-1" aria-labelledby="executiveModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="executiveModalLabel">Executive Suite</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex align-items-start">
                                <!-- Gambar Kamar -->
                                <div class="me-4" style="flex: 1;">
                                    <img src="../view_customers/img/executive.png" alt="Executive Suite" class="img-fluid rounded">
                                </div>
                                <!-- Detail Deskripsi -->
                                <div style="flex: 2;">
                                    <p>
                                        Discover the perfect blend of sophistication and comfort in our spacious Executive Suite. Designed for guests who appreciate extra room and refined elegance, this suite features a separate living area, providing privacy for work or relaxation. Ideal for business travelers or anyone seeking additional space, it offers an inviting escape where productivity and relaxation come effortlessly together.
                                    </p>
                                    <h6>Amenities:</h6>
                                    <ul class="row">
                                        <div class="col-md-6">
                                            <li>King-size bed</li>
                                            <li>Separate living area</li>
                                            <li>High-speed Wi-Fi</li>
                                            <li>Air conditioning</li>
                                            <li>Flat-screen TV</li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Minibar</li>
                                            <li>Coffee machine</li>
                                            <li>Safe</li>
                                            <li>Luxurious bathroom with bathtub</li>
                                            <li>Plush bathrobe and slippers</li>
                                            <li>Premium toiletries</li>
                                        </div>
                                    </ul>
                                    <p><strong>Price: IDR 3,500,000/Night</strong></p>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='reservasi.html';"> Payment</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--luxury-->
                <div class="container2">
                    <div class="card" style="width: 18rem;">
                        <img src="../view_customers/img/luxury.png" class="card-img-top" alt="Executive Suite">
                        <div class="card-body">
                            <h5 class="card-title">Luxury Suite</h5>
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" style="background-color: #15274b;" data-bs-toggle="modal" data-bs-target="#luxuryModal">View More</button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="luxuryModal" tabindex="-1" aria-labelledby="luxuryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="luxuryModalLabel">Luxury Suite</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex align-items-start">
                                <!-- Gambar Kamar -->
                                <div class="me-4" style="flex: 1;">
                                    <img src="/dashboard/view_customers/img/luxury.png" alt="Luxury Suite" class="img-fluid rounded">
                                </div>
                                <!-- Detail Deskripsi -->
                                <div style="flex: 2;">
                                    <p>
                                        Experience luxury on a new level in our exclusive Luxury Suite. With a private living room, a sophisticated dining area, and exquisite décor, this suite is the ideal choice for VIP guests and memorable occasions. Bask in breathtaking panoramic views and let our dedicated butler service attend to your every need, crafting an unforgettable stay marked by elegance, comfort, and seamless service.
                                    </p>
                                    <h6>Amenities:</h6>
                                    <ul class="row">
                                        <div class="col-md-6">
                                            <li>King-size bed</li>
                                            <li>Separate living and dining areas</li>
                                            <li>High-speed Wi-Fi</li>
                                            <li>Air conditioning</li>
                                            <li>Flat-screen TV</li>
                                            <li>Butler service</li>
                                            <li>Kitchenette</li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Minibar</li>
                                            <li>Coffee machine</li>
                                            <li>Safe</li>
                                            <li>Luxurious bathroom with jacuzzi</li>
                                            <li>Plush bathrobe and slippers</li>
                                            <li>Premium toiletries</li>
                                        </div>
                                    </ul>
                                    <p><strong>Price: IDR 7,500,000/Night</strong></p>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='reservasi.html';"> Payment</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--presidential-->
                <div class="container2">
                    <div class="card" style="width: 18rem;">
                        <img src="../view_customers/img/presidential.png" class="card-img-top" alt="Presidential Suite">
                        <div class="card-body">
                            <h5 class="card-title">Presidential Suite</h5>
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" style="background-color: #15274b;" data-bs-toggle="modal" data-bs-target="#presidentialModal">View More</button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="presidentialModal" tabindex="-1" aria-labelledby="presidentialModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="presidentialModalLabel">Presidential Suite</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex align-items-start">
                                <!-- Gambar Kamar -->
                                <div class="me-4" style="flex: 1;">
                                    <img src="/dashboard/view_customers/img/presidential.png" alt="Luxury Suite" class="img-fluid rounded">
                                </div>
                                <!-- Detail Deskripsi -->
                                <div style="flex: 2;">
                                    <p>
                                        Elevate your stay to unmatched heights in our Presidential Suite, the ultimate symbol of luxury and exclusivity. With expansive living and dining areas, stunning decor, and a private spa, this suite offers a world of its own. Indulge in the convenience of 24-hour butler service, unwind in your private sauna and jacuzzi, and enjoy sweeping panoramic views—all tailored for a truly unforgettable experience for the most discerning guests.
                                    </p>
                                    <h6>Amenities:</h6>
                                    <ul class="row">
                                        <div class="col-md-6">
                                            <li>King-size bed</li>
                                            <li>Grand living and dining areas</li>
                                            <li>High-speed Wi-Fi</li>
                                            <li>Air conditioning</li>
                                            <li>Flat-screen TV</li>
                                            <li>Butler service</li>
                                            <li>Kitchenette</li>
                                            <li>Panoramic views</li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Minibar</li>
                                            <li>Coffee machine</li>
                                            <li>Safe</li>
                                            <li>Elegant bathroom with jacuzzi and private sauna</li>
                                            <li>Plush bathrobe and slippers</li>
                                            <li>Premium toiletries</li>
                                        </div>
                                    </ul>
                                    <p><strong>Price: IDR 15,000,000/Night</strong></p>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='reservasi.html';"> Payment</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!--section facilites-->
        
        <!-- judul -->
        <section id="facilites">
            <header class="title">
                <h1 style="color: #2c5099; font-family: 'Orelega One', cursive; margin-top: 20px; margin-bottom: 20px; letter-spacing: 5px;"> FACILITES </h1>
            </header>
        
            <!-- content 1 -->
            <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <img src="../view_customers/img/fitnees.png" class="img-fluid" alt="Modern fitness center with infinity pool">
                </div>
                <div class="col-md-6" >
                    <h3 style="color: #2c5099;font-family: 'Orelega One', cursive;">Fitness Center and Pool</h3>
                    <p style="font-family: 'Luxurious Roman', cursive; text-align: justify;">Recharge in our modern fitness center with top-tier equipment and personal training options. Then, relax by the infinity pool with panoramic views, plush loungers, and poolside service for a refreshing experience.</p>
                </div>
            </div>
                <!-- content 2 -->
                <div class="container">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6 order-md-2">
                        <img src="../view_customers/img/spa.png" class="img-fluid" alt="Spa and wellness center with relaxation lounge">
                    </div>
                    <div class="col-md-6 order-md-1">
                        <h3 style="color: #2c5099; font-family: 'Orelega One', cursive;">Spa and Wellness Center</h3>
                        <p style="font-family: 'Luxurious Roman', cursive; text-align: justify;">Indulge in ultimate relaxation at our spa, offering a wide range of treatments from massages to facials. Our wellness center includes a sauna, steam room, and relaxation lounge, providing a sanctuary for rejuvenation.</p>
                    </div>
                </div>
                <!-- content 3 -->
                <div class="container">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <img src="../view_customers/img/gourment dining.png" class="img-fluid" alt="Fine dining restaurant with elegant decor">
                    </div>
                    <div class="col-md-6">
                        <h3 style="color: #2c5099; font-family: 'Orelega One', cursive;">Gourmet Dining and Exclusive Bars</h3>
                        <p style="font-family: 'Luxurious Roman', cursive; text-align: justify;">Savor world-class cuisine crafted by our expert chefs at our fine dining restaurants, where every dish is an exquisite fusion of flavors and artistry. Relax at our upscale bars, where master mixologists prepare signature cocktails and premium wines are always in good company.</p>
                    </div>
                </div>
            </div>
        </section>

        <!--section additional services-->
        <section id="additionalservices">
            <header class="title">
                <h1 style="color: #2c5099; font-family: 'Orelega One', cursive; margin-top: 100px; margin-bottom: 20px; letter-spacing: 5px;"> ADDITIONAL SERVICES </h1>
            </header>
            <div class="container">
            <div class="row" style="margin-top: 40px;">
                <div class="col-md-6">
                 <div class="service-card" style="background-color: #757A92; color: #F5F0DD; text-align: center; padding: 20px; margin-bottom: 20px; height: 250px;">
                  <img alt="A table set for in-room dining with a waiter in the background" src="../view_customers/img/personalized in roon dining.png" style="width: 100%; height: 150px; object-fit: cover;"/>
                  <p style="font-family:'Orelega One'; font-size: larger;">Personalized In-Room Dining</p>
                 </div>
                </div>
                <div class="col-md-6">
                 <div class="service-card" style="background-color: #757A92; color: #F5F0DD; text-align: center; padding: 20px; margin-bottom: 20px; height: 250px;">
                  <img alt="A personal fitness trainer assisting a person in a gym" src="../view_customers/img/personal fitness training.png" style="width: 100%; height: 150px; object-fit: cover;"/>
                  <p style="font-family:'Orelega One'; font-size: larger;">Personal Fitness Trainer &amp; Wellness Coach</p>
                 </div>
                </div>
                <div class="col-md-6">
                 <div class="service-card" style="background-color: #757A92; color: #F5F0DD; text-align: center; padding: 20px; margin-bottom: 20px; height: 250px;">
                  <img alt="A person receiving a spa treatment in a luxurious room" src="../view_customers/img/247 spa services.png" style="width: 100%; height: 150px; object-fit: cover;"/>
                  <p style="font-family:'Orelega One'; font-size: larger;">24/7 In-Room Spa Services</p>
                 </div>
                </div>
                <div class="col-md-6">
                 <div class="service-card" style="background-color: #757A92; color: #F5F0DD; text-align: center; padding: 20px; margin-bottom: 20px; height: 250px;">
                  <img alt="Children participating in activities at a kids club" src="../view_customers/img/kids club.png" style="width: 100%; height: 150px; object-fit: cover;"/>
                  <p style="font-family:'Orelega One'; font-size: larger;">Exclusive Kids Club with Personalized Activities</p>
                 </div>
                </div>
               </div>
               <hr style="border: 2px solid #15274B; margin-top: 20px;"/>
               <div class="row contact-info" style="margin-top: 20px;">
                <div class="col-md-4">
                 <p style="font-family: 'Orelega One', cursive; color: #15274B;"><strong>CONTACT US</strong></p>
                 <p class="details" style="margin: 0; font-size: 14px; color: #15274B; font-family: 'Plus Jakarta Sans', sans-serif;">Jl. Iskandarsyah Raya No. 65 Jakarta 12160 </p>
                 <p class="details" style="margin: 0; font-size: 14px; color: #15274B; font-family: 'Plus Jakarta Sans', sans-serif;">Fax: 62-21 29126277</p>
                 <p class="details" style="margin: 0; font-size: 14px; color: #15274B; font-family: 'Plus Jakarta Sans', sans-serif;">Email: MoonlitHotel@gmail.com</p>
                </div>
                <div class="col-md-2 social-media" style="margin-top: 20px;">
                 <p style="font-family: 'Orelega One', cursive; color: #15274B;"><strong>SOCIAL MEDIA</strong></p>
                 <a href=""><i class="fab fa-instagram" style="font-size: 24px; color: #007bff; margin-right: 10px;"> </i></a>
                 <a href=""><i class="fab fa-facebook" style="font-size: 24px; color: #007bff; margin-right: 10px;"></i></a>
                </div>
                <div class="col-md-6 review" style="margin-top: 20px;">
                 <p style="font-family: 'Orelega One', cursive; color: #2C5099; letter-spacing: 2px;"><strong>Review</strong></p>
                 <textarea placeholder="Share your experience" style="width: 100%; height: 100px; margin-bottom: 10px;"></textarea>
                 <button class="btn" style="float: right; width: 100px; height: 40px; border-radius: 0; font-family: 'Plus Jakarta Sans', sans-serif; background-color: #15274B; color: white;">Send</button>
                </div>
                
               </div>
               <hr style="border: 1px solid #2c3e50; margin-top: 20px;"/>
               <div class="footer" style="text-align: center; margin-top: 20px; font-size: 12px; color: #2c3e50;">
                <p>© Copyright 2024. All Rights Reserved by Moonlit Hotel</p>
               </div>
              </div>
            </div>
        </section>

        <!--contact us-->
        <section id="contactus">
        </section>
    
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
