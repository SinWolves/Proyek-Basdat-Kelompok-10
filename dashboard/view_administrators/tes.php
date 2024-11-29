<?php
    include '../conn.php';

    // Query untuk mengambil data dari database
    if($_SERVER['REQUEST_METHOD']==='POST'){
  
      $nama = $_POST['nama'];
      
      $stmt = $pdo->prepare("INSERT INTO tes(nama) VALUES (:nama)");
      $stmt->bindParam(':nama', $nama);
    
      $stmt->execute();
        echo "Buku Berhasil Ditambahkan";
      header("Location: " . $_SERVER['PHP_SELF']); 
      exit();
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mahasiswa Lab 3</title>
</head>
<body>
  <h2>Data Mahasiswa Lab Pemrograman WEB 3</h2>

  <table style="border: 1px solid #000000;">
    <tr>
      <th>id</th>
      <th>Nama</th>
    </tr>

    <?php 
    $managers = [];
    try {
        $stmt = $pdo->query("SELECT * FROM tes ORDER BY id");
        $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $message = "Error fetching data: " . $e->getMessage();
    }
    ?>
    <?php if(!empty($managers)) : ?>
      <?php foreach($managers as $manager): ?>
        <tr>
        <td><?php echo htmlspecialchars($manager['id']); ?></td>
        <td><?php echo htmlspecialchars($manager['nama']); ?></td>
    </tr>
      <?php endforeach; ?>
      <?php else : ?>
        <tr><td>no data</td></tr>
      <?php endif; ?>
  </table>

  <form action="" method="POST">
    <label for="nama">input nama:</label>
    <input type="text" name="nama"> 
    <button type="submit">submit</button>
  </form>
</body>
</html>