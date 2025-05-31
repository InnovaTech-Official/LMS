<?php
// Database connection
include_once __DIR__ . '/../../../includes/db_wrapper.php';

// Function to fetch all bank accounts
function getAllBankAccounts() {
    global $pdo;
    $sql = "SELECT * FROM bank_info ORDER BY id DESC";
    $result = $pdo->query($sql);
    
    $data = [];
    if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
    }
    
    $pdo = null;
    return $data;
}
// Function to fetch a single bank account by ID
function getBankAccountById($id) {
    global $pdo;
    
    $sql = "SELECT * FROM bank_info WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $data = null;
    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    $pdo = null;
    return $data;
}
?>