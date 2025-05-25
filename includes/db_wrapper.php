<?php
/**
 * Database Wrapper for seamless offline/online transitions
 * This file provides a unified interface for database operations with automatic fallback
 */

// Database wrapper functions to centralize database operations
// This file includes local_db.php which contains the actual connection

// Include database connection file
require_once __DIR__ . '/local_db.php';

/**
 * Execute a query and return all rows
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for prepared statement
 * @return array Result rows
 */
function db_get_rows($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Query error in db_get_rows: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Execute a query and return a single row
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for prepared statement
 * @return array|false Single result row or false if no row
 */
function db_get_row($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Query error in db_get_row: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Execute a query (INSERT, UPDATE, DELETE)
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function db_query($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Query error in db_query: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get the ID of the last inserted row
 * @return string Last inserted ID
 */
function db_last_insert_id() {
    global $pdo;
    return $pdo->lastInsertId();
}

/**
 * Begin a transaction
 */
function db_begin_transaction() {
    global $pdo;
    $pdo->beginTransaction();
}

/**
 * Commit a transaction
 */
function db_commit() {
    global $pdo;
    $pdo->commit();
}

/**
 * Rollback a transaction
 */
function db_rollback() {
    global $pdo;
    $pdo->rollBack();
}
