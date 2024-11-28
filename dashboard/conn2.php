<?php
// Supabase API URL and key
$supabase_url = 'https://uvybzqrpehswewlmhkex.supabase.co';
$api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InV2eWJ6cXJwZWhzd2V3bG1oa2V4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzI1ODUxNDAsImV4cCI6MjA0ODE2MTE0MH0.0m1gQ9zJVgM1HkPK-Dlvg2NawRds4JJjH7emtMmMQ94';

// The RPC endpoint for executing SQL queries
$url = $supabase_url . '/rest/v1/' . 'tes';

// SQL query to get table names
$query = json_encode([
    "sql" => "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';"
]);

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "apikey: $api_key",
    "Authorization: Bearer $api_key"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

// Execute the request
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    $data = json_decode($response, true);
    print_r($data);
}

curl_close($ch);
?>
