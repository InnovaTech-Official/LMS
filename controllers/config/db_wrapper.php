<?php
/**
 * Database Wrapper for seamless offline/online transitions
 * This file provides a unified interface for database operations with automatic fallback
 */

// Initially try to connect to remote database
$connection_initialized = false;
$is_remote = false;

// Connection object
$pdo = null;

/**
 * Initialize database connection with automatic fallback
 */
function initialize_connection() {
    global $pdo, $connection_initialized, $is_remote;
    
    if ($connection_initialized) {
        return true;
    }
    
    // Try remote connection first with a short timeout
    try {
        // Set a very short timeout for the initial connection attempt
        ini_set('default_socket_timeout', 3);
        
        // Include remote config which has built-in fallback to local
        include_once __DIR__ . '/remote_config.php';
        
        // If we get here and $using_remote is true (from remote_config.php), 
        // then we're connected to the remote database
        $is_remote = isset($using_remote) ? $using_remote : false;
        $connection_initialized = true;
        
        // Reset timeout to default
        ini_set('default_socket_timeout', 60);
        
        return true;
    } catch (Exception $e) {
        // If there's an error at this level, force local connection
        include_once __DIR__ . '/local_config.php';
        $is_remote = false;
        $connection_initialized = true;
        
        // Reset timeout to default
        ini_set('default_socket_timeout', 60);
        
        error_log("Critical error in initialize_connection: " . $e->getMessage());
        return false;
    }
}

/**
 * Check connection health and automatically switch to local if remote is down
 */
function check_connection() {
    global $pdo, $is_remote;
    
    if (!$is_remote) {
        // Already on local, nothing to check
        return true;
    }
    
    try {
        // Quick check if connection is still alive
        $stmt = $pdo->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        // Remote connection lost, switch to local
        include_once __DIR__ . '/local_config.php';
        $is_remote = false;
        error_log("Lost connection to remote database, switched to local: " . $e->getMessage());
        return false;
    }
}

/**
 * Execute a query with automatic connection check and fallback
 * 
 * @param string $sql The SQL query
 * @param array $params Parameters for prepared statement
 * @return PDOStatement|false The query result
 */
function db_query($sql, $params = []) {
    global $pdo;
    
    // Make sure connection is initialized
    if (!initialize_connection()) {
        // Critical connection error
        throw new Exception("Could not establish database connection");
    }
    
    // Check connection health before executing query
    check_connection();
    
    try {
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        // If query fails, check connection again
        if (check_connection()) {
            // Try again with new connection
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return $stmt;
            } catch (PDOException $inner_e) {
                throw $inner_e; // If still fails, throw the error
            }
        }
        throw $e;
    }
}

/**
 * Get a single row from the database
 */
function db_get_row($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetch();
}

/**
 * Get all rows from the database
 */
function db_get_all($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Insert data into the database
 */
function db_insert($table, $data) {
    global $pdo;
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    $stmt = db_query($sql, array_values($data));
    return $pdo->lastInsertId();
}

/**
 * Check if we're currently using remote or local database
 */
function is_using_remote_database() {
    global $is_remote;
    initialize_connection();
    return $is_remote;
}

// Initialize the connection when this file is included
initialize_connection();