<?php
session_start();
require_once '../../../controllers/config/db_wrapper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Handle form submission for editing bank info
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $bank_name = $_POST['bank_name'] ?? '';
    $branch_name = $_POST['branch_name'] ?? '';
    $branch_code = $_POST['branch_code'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $account_title = $_POST['account_title'] ?? '';
    $iban = $_POST['iban'] ?? '';
    $swift_code = $_POST['swift_code'] ?? '';
    $bank_address = $_POST['bank_address'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email = $_POST['email'] ?? '';

    // Update bank info
    $stmt = $pdo->prepare("UPDATE bank_info SET 
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
        WHERE id = :id");

    // Bind parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':bank_name', $bank_name);
    $stmt->bindParam(':branch_name', $branch_name);
    $stmt->bindParam(':branch_code', $branch_code);
    $stmt->bindParam(':account_number', $account_number);
    $stmt->bindParam(':account_title', $account_title);
    $stmt->bindParam(':iban', $iban);
    $stmt->bindParam(':swift_code', $swift_code);
    $stmt->bindParam(':bank_address', $bank_address);
    $stmt->bindParam(':contact_person', $contact_person);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Bank information updated successfully!";
    } else {
        $error_info = $stmt->errorInfo();
        $_SESSION['error_message'] = "Error updating bank information: " . $error_info[2];
    }
    
    header('Location: ../../../views/setup/banksetup/index.php');
    exit;
}
?>