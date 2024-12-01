<?php
session_start(); 
include 'config.php'; // File untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    // Cek apakah username sudah terdaftar
    $cek_user = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");
    if (!$cek_user) {
        die("Query Error: " . mysqli_error($conn));
    }
    $cek_login = mysqli_num_rows($cek_user);

    if ($cek_login > 0) {
        echo "<script>
            alert('Username telah terdaftar');
            window.location = 'signup.php';
        </script>";
    } else {
        // Cek apakah password cocok
        if ($password !== $password2) {
            echo "<script>
                alert('Password tidak sesuai');
                window.location = 'signup.php';
            </script>";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data ke tabel akun
            $insert = mysqli_query($conn, "INSERT INTO akun (name, email, username, password) VALUES ('$name', '$email', '$username', '$hashed_password')");
            if ($insert) {
                echo "<script>
                    alert('Data berhasil ditambahkan');
                    window.location = 'index.php';
                </script>";
            } else {
                echo "<script>
                    alert('Error: " . mysqli_error($conn) . "');
                    window.location = 'signup.php';
                </script>";
            }
        }
    }
}
?>


<!--ini adalah kerangka dari html--> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <main>
        <div class="signup-box">
            <h1>Sign Up</h1>
            <form action="signup.php" method="POST"> 
                <div class="name">
                    <label for="name">Name</label><br>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" required>
                        <i class="fas fa-user"></i> <!-- User icon -->
                    </div>
                </div>
                <div class="e-mail">
                    <label for="email">Email</label><br>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                <div class="username">
                    <label for="username">Username</label><br>
                    <div class="input-wrapper">
                        <input type="text" name="username" id="username" required>
                        <i class="fas fa-user"></i> <!-- User icon -->
                    </div>
                </div>
                <div class="secret">
                    <label for="password">Password</label><br>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" required>
                        <i class="fas fa-lock"></i> <!-- Lock icon -->
                    </div>
                    <div class="confirm">
                <label for="password">Cornfirm Password</label><br>
                <div class="input-wrapper">
                    <input type="password" name="password2" id="password2">
                    <i class="fas fa-lock"></i> <!-- Lock icon -->
                </div>
                </div>
                <button type="submit" value="SIGNUP" name="submit">Sign Up</button>
            </form>
        </div>
        <div class="sign-up">
            <a href="login.pho">Already Have an Account? Login</a>
        </div>
    </main>
</body>
</html>
