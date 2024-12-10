<?php
session_start(); 
include '../conn.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']); // Menggunakan MD5 untuk hashing (sebaiknya gunakan bcrypt pada sistem nyata)

    // Query untuk mengecek username dan password
    $query = "SELECT * FROM akun WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($password, $result['password'])) {
            $_SESSION['nama'] = $result['name'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['phone'] = "-";
            $_SESSION['username'] = $result['username'];
            $_SESSION['birth'] = "-";
            $_SESSION['id_akun'] = $result['id'];

            if($result['role'] == 'admin') {
                header("Location: ../view_administrators/dashboard.php");
                exit();
            }else{
                header("Location: index.php");

                exit();
            }

           // header("Location: index.php");
            //exit();
        } else {
            echo "<script>alert('Username atau password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
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
                <button type="submit" name="login">Login</button>
            </form>
        </div>
        <div class="sign-up">
            <a href="signup.php">Don't Have an Account Yet? Sign Up</a>
        </div>
    </main>
</body>
</html>
