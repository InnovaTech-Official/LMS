<?php
require_once __DIR__ . '/../../../models/setup/banksetup/fetch.php';
require_once __DIR__ . '/../../../includes/db_wrapper.php';

function addBankAccount($data) {
    global $pdo;
    
    // Prepare SQL statement
    $sql = "INSERT INTO bank_info (bank_name, branch_name, branch_code, account_number, account_title, 
            iban, swift_code, bank_address, contact_person, contact_number, email) 
            VALUES (:bank_name, :branch_name, :branch_code, :account_number, :account_title, 
            :iban, :swift_code, :bank_address, :contact_person, :contact_number, :email)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bank_name', $data['bank_name']);
        $stmt->bindParam(':branch_name', $data['branch_name']);
        $stmt->bindParam(':branch_code', $data['branch_code']);
        $stmt->bindParam(':account_number', $data['account_number']);
        $stmt->bindParam(':account_title', $data['account_title']);
        $stmt->bindParam(':iban', $data['iban']);
        $stmt->bindParam(':swift_code', $data['swift_code']);
        $stmt->bindParam(':bank_address', $data['bank_address']);
        $stmt->bindParam(':contact_person', $data['contact_person']);
        $stmt->bindParam(':contact_number', $data['contact_number']);
        $stmt->bindParam(':email', $data['email']);
        
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = addBankAccount($_POST);
    
    // Redirect with appropriate message
    if ($result === true) {
        header("Location: ../../../views/setup/banksetup/index.php?success=Account added successfully");
    } else {
        header("Location: ../../../views/setup/banksetup/index.php?error=" . urlencode($result));
    }
    exit();
}
?>