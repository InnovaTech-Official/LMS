<?php
// config.php - PostgreSQL database connection configuration
$servername = "localhost";
$username = "postgres";  // default postgres user
$password = "root";      // your PostgreSQL password
$dbname = "loan_db";
$port = "5432";         // default PostgreSQL port

try {
    // Check if PostgreSQL extension is installed
    if (!extension_loaded('pgsql') && !extension_loaded('pdo_pgsql')) {
        throw new Exception("PostgreSQL PHP extensions are not installed. Please enable pgsql and pdo_pgsql extensions in php.ini");
    }
    
    // Use pgsql instead of mysql in DSN for PostgreSQL with explicit port
    $pdo = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // For fallback system to track that we're using local database
    $using_remote = false;
    
} catch(PDOException $e) {
    // Log the specific database connection error
    error_log('PostgreSQL connection error: ' . $e->getMessage());
    
    // Check if the database exists - common error
    if (strpos($e->getMessage(), 'database "loan_db" does not exist') !== false) {
        die(json_encode(['error' => 'Database "loan_db" does not exist. Please create it in PostgreSQL.']));
    }
    
    // Check for authentication failures
    if (strpos($e->getMessage(), 'password authentication failed') !== false) {
        die(json_encode(['error' => 'PostgreSQL authentication failed. Check your username and password.']));
    }
    
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
} catch(Exception $e) {
    error_log('Configuration error: ' . $e->getMessage());
    die(json_encode(['error' => $e->getMessage()]));
}
