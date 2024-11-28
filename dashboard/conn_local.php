<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "basdat";

$conn = new mysqli($server, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>