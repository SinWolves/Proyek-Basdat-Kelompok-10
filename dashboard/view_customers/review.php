<?php
include '../conn.php';

session_start();

$id_akun = htmlspecialchars($_POST['id_akun']); 
$review = htmlspecialchars($_POST['review']);

$stmt = $pdo->prepare("INSERT INTO review(id_akun, review) 
                        VALUES (:id_akun, :review)");
$stmt->bindParam(':id_akun', $id_akun);
$stmt->bindParam(':review', $review);
$stmt->execute();

header("Location: index.php");
exit();
?>