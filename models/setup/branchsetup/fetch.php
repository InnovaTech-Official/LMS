<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

function fetchData($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM branches ORDER BY id DESC");
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
        ]; }
}

   