<?php
include '../../conn_local.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
  
  $nama = $_POST['nama'];
  $departemen = $_POST['departemen'];
  $telepon = (int) $_POST['telepon'];
  $alamat = $_POST['alamat'];
  
  $stmt = $conn->prepare("INSERT INTO manajer (nama, departemen, telepon, alamat) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $nama, $departemen, $telepon, $alamat);

  $stmt->execute();
    echo "Success";
  $stmt->close();
  $conn->close();
}
?>