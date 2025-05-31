<?php
// config.php for local PostgreSQL connection
$servername = "localhost";
$port = "5432";
$username = "postgres";
$password = "1234";
$dbname = "loan_db";

try {
    $pdo = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // For fallback system to track that we're using local database
    $using_remote = false;

} catch (PDOException $e) {
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}
