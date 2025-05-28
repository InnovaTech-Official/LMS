<?php
// config.php with automatic fallback to local PostgreSQL database
$timeout = 3; // 3 seconds timeout for faster fallback

// Remote PostgreSQL database configuration
$servername = "localhost";
$port = "5432";
$username = "postgres";
$password = "root";
$dbname = "loan_db";

// Flag to track if we're using remote or local connection
$using_remote = true;

try {
    // Set options
    $options = [
        PDO::ATTR_TIMEOUT => $timeout, // Works for some drivers, but not officially supported in pgsql
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    // Create PDO object for PostgreSQL
    $pdo = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password, $options);

    // Additional check to verify the connection is responsive
    $stmt = $pdo->query("SELECT 1");
} catch (PDOException $e) {
    // Fallback to local config
    $using_remote = false;

    include_once __DIR__ . '/local_config.php';

    error_log('Remote PostgreSQL connection failed, falling back to local: ' . $e->getMessage());
}

/**
 * Function to check if connection is still active and fallback if needed
 */
function ensure_connection() {
    global $pdo, $using_remote;

    if ($using_remote) {
        try {
            $stmt = $pdo->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            $using_remote = false;
            include_once __DIR__ . '/local_config.php';
            error_log('Remote PostgreSQL connection lost during operation, fell back to local: ' . $e->getMessage());
            return false;
        }
    }
    return true;
}

/**
 * Function to execute queries with automatic fallback
 */
function execute_query($query, $params = []) {
    global $pdo;

    try {
        ensure_connection(); // Check connection
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        if (ensure_connection()) {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }
        throw $e;
    }
}
