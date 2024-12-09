<?php
include '../conn.php'; // File untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    // Validasi input
    $errors = [];
    
    if (empty($name)) $errors[] = "Nama tidak boleh kosong";
    if (empty($email)) $errors[] = "Email tidak boleh kosong";
    if (empty($username)) $errors[] = "Username tidak boleh kosong";
    if (empty($password)) $errors[] = "Password tidak boleh kosong";
    if ($password !== $password2) $errors[] = "Password tidak sesuai";

    // Cek apakah username sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM akun WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $cek_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cek_user) {
        $errors[] = "Username telah terdaftar";
    }

    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM akun WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $cek_email = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cek_email) {
        $errors[] = "Email telah terdaftar";
    }

    // Jika ada error, tampilkan pesan
    if (!empty($errors)) {
        $error_message = implode("\\n", $errors);
        echo "<script>
            alert('$error_message');
            window.location = 'signup.php';
        </script>";
        exit();
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan data ke tabel akun dengan role default 'user'
    $insert = $pdo->prepare("INSERT INTO akun (name, email, username, password, role) VALUES (:name, :email, :username, :hashed_password, :role)");
    try {
        $insert->execute([
            ":name" => $name,
            ":email" => $email,
            ":username" => $username,
            ":hashed_password" => $hashed_password,
            ":role" => 'user' // Tambahkan role default
        ]);

        echo "<script>
            alert('Registrasi berhasil');
            window.location = 'login.php';
        </script>";
        exit();
    } catch (PDOException $e) {
        // Handle any errors that occur during the insert process
        echo "<script>
            alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');
            window.location = 'signup.php';
        </script>";
        exit();
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
            <a href="login.php">Already Have an Account? Login</a>
        </div>
    </main>
</body>
</html>
