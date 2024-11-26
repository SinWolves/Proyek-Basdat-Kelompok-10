<?php
    require_once '../conn.php';

    $tes = $pdo->query("SELECT * FROM tes");
    $data = $tes->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Ini datanya</h1><br>";
    foreach ($data as $nama){
        echo "Id : {$nama['id']} - nama : {$nama['nama']}<br>";
    }
?>