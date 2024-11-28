<?php
    require_once '../conn.php';

    $tes = $pdo->query("SELECT * FROM tes");
    $data = $tes->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Ini datanya</h1><br>";
    foreach ($data as $nama){
        echo "Id : {$nama['id']} - nama : {$nama['nama']}<br>";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Data from Supabase</h1>

<!-- Create a container to display data -->
<div id="data-container"></div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Make sure that the script is loaded before using the `createClient`
            const supabase = supabase.createClient(
                'https://uvybzqrpehswewlmhkex.supabase.co',
                'your-api-key-here'
            );

            // Function to fetch data from Supabase
            async function fetchData() {
                console.log('Fetching data from Supabase...');

                const { data, error } = await supabase.from('tes').select('*');

                // Check for errors
                if (error) {
                    console.error('Error fetching data:', error);
                    document.getElementById('data-container').innerHTML = 'Error fetching data';
                    return;
                }

                // Display the data in the HTML
                const container = document.getElementById('data-container');
                if (data.length === 0) {
                    container.innerHTML = 'No data found';
                } else {
                    let table = '<table border="1"><tr><th>ID</th><th>Name</th><th>Age</th></tr>';
                    data.forEach(row => {
                        table += `<tr><td>${row.id}</td><td>${row.name}</td><td>${row.age}</td></tr>`;
                    });
                    table += '</table>';
                    container.innerHTML = table;
                }
            }

            // Fetch
            fetchData();
        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
</body>
</html>