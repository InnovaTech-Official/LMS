<?php
/**
 * PostgreSQL-Compatible Database Wrapper
 * Supports seamless remote/local PostgreSQL connections
 */

$connection_initialized = false;
$is_remote = false;
$pdo = null;

function initialize_connection() {
    global $pdo, $connection_initialized, $is_remote;

    if ($connection_initialized) return true;

    try {
        ini_set('default_socket_timeout', 3);

        include_once __DIR__ . '/remote_config.php';

        $is_remote = isset($using_remote) ? $using_remote : false;
        $connection_initialized = true;

        ini_set('default_socket_timeout', 60);
        return true;
    } catch (Exception $e) {
        include_once __DIR__ . '/local_config.php';
        $is_remote = false;
        $connection_initialized = true;

        ini_set('default_socket_timeout', 60);
        error_log("Critical error in initialize_connection: " . $e->getMessage());
        return false;
    }
}

function check_connection() {
    global $pdo, $is_remote;

    if (!$is_remote) return true;

    try {
        $stmt = $pdo->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        include_once __DIR__ . '/local_config.php';
        $is_remote = false;
        error_log("Lost connection to remote database, switched to local: " . $e->getMessage());
        return false;
    }
}

function db_query($sql, $params = []) {
    global $pdo;

    if (!initialize_connection()) {
        throw new Exception("Could not establish database connection");
    }

    check_connection();

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        if (check_connection()) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return $stmt;
            } catch (PDOException $inner_e) {
                throw $inner_e;
            }
        }
        throw $e;
    }
}

function db_get_row($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_get_all($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function db_insert($table, $data, $id_column = 'id') {
    global $pdo;
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders) RETURNING $id_column";

    $stmt = db_query($sql, array_values($data));
    return $stmt->fetchColumn(); // PostgreSQL: returns inserted ID
}

function is_using_remote_database() {
    global $is_remote;
    initialize_connection();
    return $is_remote;
}

initialize_connection();
