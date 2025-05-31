<?php
require_once __DIR__ . '/../../../models/setup/banksetup/fetch.php';

// Function to update an existing bank account
function updateBankAccount($data, $pdo) {
    
    // Prepare SQL query with parameters
    $sql = "UPDATE bank_info SET 
            bank_name = :bank_name, 
            branch_name = :branch_name, 
            branch_code = :branch_code, 
            account_number = :account_number, 
            account_title = :account_title, 
            iban = :iban, 
            swift_code = :swift_code, 
            bank_address = :bank_address, 
            contact_person = :contact_person, 
            contact_number = :contact_number, 
            email = :email 
            WHERE id = :id";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([
        ':id' => $data['id'],
        ':bank_name' => $data['bank_name'],
        ':branch_name' => $data['branch_name'],
        ':branch_code' => $data['branch_code'],
        ':account_number' => $data['account_number'],
        ':account_title' => $data['account_title'],
        ':iban' => $data['iban'],
        ':swift_code' => $data['swift_code'],
        ':bank_address' => $data['bank_address'],
        ':contact_person' => $data['contact_person'],
        ':contact_number' => $data['contact_number'],
        ':email' => $data['email']
    ])) {
        return true;
    } else {
        $error = $stmt->errorInfo();
        return $error[2];
    }
}

// Process form submission
require_once __DIR__ . '/../../../includes/db_wrapper.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit']) && $_POST['edit'] == "1") {
    $result = updateBankAccount($_POST, $pdo);
    
    // Redirect with appropriate message
    if ($result === true) {
        header("Location: ../../../views/setup/banksetup/index.php?success=Account updated successfully");
    } else {
        header("Location: ../../../views/setup/banksetup/index.php?error=" . urlencode($result));
    }
    exit();
}
?>