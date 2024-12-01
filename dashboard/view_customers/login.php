<?php
session_start(); 
include 'config.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = ($_POST['password']); // Menggunakan MD5 untuk hashing (sebaiknya gunakan bcrypt pada sistem nyata)

    // Query untuk mengecek username dan password
    $query = mysqli_query($conn, "SELECT * FROM akun WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {
        // Jika ditemukan user
        $data = mysqli_fetch_array($query); 
        $_SESSION['akun'] = $data; // Menyimpan data user ke sesi
        echo "<script>
                alert('Selamat datang, {$data['username']}');
                location.href = 'index.php';
              </script>";
    } else {
        // Jika username atau password salah
        echo "<script>
                alert('Username atau password Anda tidak sesuai!');
                location.href = 'login.php';
              </script>";
    }
}
?>


<!--untuk kerangka html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <main>
        <div class="login-box">
            <h1>Login</h1>
            <form action="login.php" method="POST">
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
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="sign-up">
            <a href="signup.php">Don't Have an Account Yet? Sign Up</a>
        </div>
    </main>
</body>
</html>
