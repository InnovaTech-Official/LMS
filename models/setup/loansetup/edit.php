<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

function getId($id, $pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM loan_products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // single row

        return $result;
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// --- START main logic ---
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$loan = getId($id, $pdo);

if (isset($loan['error'])) {
    die("Error fetching loan: " . $loan['error']);
}

$isEdit = true; // so your form knows it's in edit mode

// include your form view
include('../../../views/setup/loansetup/editLoan.php');
