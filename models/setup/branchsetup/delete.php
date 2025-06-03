<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

function deleteId($id, $pdo)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM branches WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ['success' => true, 'message' => 'Branch deleted successfully'];
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$loan = deleteId($id, $pdo);

if (isset($loan['error'])) {
    die("Error fetching loan: " . $loan['error']);
}

$isEdit = true;
