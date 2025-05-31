<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

function deleteId($id, $pdo)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM loan_products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 

        return $result;
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

