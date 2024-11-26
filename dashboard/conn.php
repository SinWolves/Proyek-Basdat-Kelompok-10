<?php
define('DB_HOST', 'aws-0-ap-southeast-1.pooler.supabase.com');
define('DB_PORT', '6543');
define('DB_NAME', 'postgres'); 
define('DB_USER', 'postgres.uvybzqrpehswewlmhkex'); 
define('DB_PASS', 'proyekbasdat6'); 

try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>