<?php
    include '../conn_local.php';

    // Query untuk mengambil data dari database
$query = "SELECT * FROM tes";
$result = mysqli_query($conn, $query);

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
      <th>NIM</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Alamat</th>
      <th>Telepon</th>
    </tr>

    <?php 
    if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["a"] . "</td>";
        echo "<td>" . $row["b"] . "</td>";
        echo "<td>" . $row["c"] . "</td>";
        echo "<td>" . $row["d"] . "</td>";
        echo "</tr>";
      } 
    } else {
      echo "<tr><td> Tidak ada data yang tampil<td></tr>";
    }

    mysqli_close($conn);
    ?>

</body>
</html>