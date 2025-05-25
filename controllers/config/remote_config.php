<?php
// config.php with automatic fallback to local database
// Define connection timeout for quick detection of connection loss
$timeout = 3; // 3 seconds timeout for faster fallback

// Remote database configuration
$servername = "45.84.205.0";
$username = "u534616354_retail";
$password = "wRri]?v?;A8/";
$dbname = "u534616354_retail";

// Flag to track if we're using remote or local connection
$using_remote = true;

try {
    // Set timeout options for faster detection of connection issues
    $options = [
        PDO::ATTR_TIMEOUT => $timeout,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, $options);
    
    // Additional check to verify the connection is responsive
    $stmt = $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    // If remote connection fails, fall back to local config
    $using_remote = false;
    
    // Include local config
    include_once __DIR__ . '/local_config.php';
    
    // Log the fallback (optional)
    error_log('Remote database connection failed, falling back to local: ' . $e->getMessage());
}

// Function to check if connection is still active and fallback if needed
function ensure_connection() {
    global $pdo, $using_remote;
    
    if ($using_remote) {
        try {
            // Quick test query to see if connection is still alive
            $stmt = $pdo->query("SELECT 1 LIMIT 1");
            return true; // Connection is good
        } catch (PDOException $e) {
            // Connection lost, fall back to local
            $using_remote = false;
            include_once __DIR__ . '/local_config.php';
            error_log('Remote database connection lost during operation, fell back to local: ' . $e->getMessage());
            return false; // Indicates a fallback occurred
        }
    }
    return true; // Already using local connection
}

// Function to execute queries with automatic fallback
function execute_query($query, $params = []) {
    global $pdo;
    
    try {
        ensure_connection(); // Check connection before executing
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        // Try fallback one more time
        if (ensure_connection()) {
            // Try the query again with the new connection
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }
        // If still failing, throw the error
        throw $e;
    }
}
