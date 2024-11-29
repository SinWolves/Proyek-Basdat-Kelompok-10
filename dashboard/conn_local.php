<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "basdat";

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
else{
    echo "good";
}
?>