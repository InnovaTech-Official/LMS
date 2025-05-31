<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

/**
 * Insert new loan settings
 * 
 * @param PDO $pdo Database connection
 * @param array $data Form data
 * @return array Result with success status and message
 */

function fetchData($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM loan_products ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            return ['success' => true, 'data' => $data];
        } else {
            return ['success' => false, 'data' => []];
        }
    } catch (PDOException $e) {
        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}
