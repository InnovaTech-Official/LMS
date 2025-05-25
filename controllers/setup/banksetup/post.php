<?php
session_start();
require_once '../../../controllers/config/db_wrapper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Create bank_info table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS bank_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(255) NOT NULL,
    branch_name VARCHAR(255),
    branch_code VARCHAR(50),
    account_number VARCHAR(50) NOT NULL,
    account_title VARCHAR(255) NOT NULL,
    iban VARCHAR(50),
    swift_code VARCHAR(50),
    bank_address TEXT,
    contact_person VARCHAR(255),
    contact_number VARCHAR(50),
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($create_table_query);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error creating table: " . $e->getMessage();
    header('Location: ../../../views/setup/banksetup/index.php');
    exit;
}

// Handle form submission for adding new bank info
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Insert new bank info
    $stmt = $pdo->prepare("INSERT INTO bank_info (
        bank_name, branch_name, branch_code, account_number, account_title,
        iban, swift_code, bank_address, contact_person, contact_number, email)
        VALUES (
        :bank_name, :branch_name, :branch_code, :account_number, :account_title,
        :iban, :swift_code, :bank_address, :contact_person, :contact_number, :email)");

    // Bind parameters
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
        $_SESSION['success_message'] = "Bank information saved successfully!";
    } else {
        $error_info = $stmt->errorInfo();
        $_SESSION['error_message'] = "Error saving bank information: " . $error_info[2];
    }
    
    header('Location: ../../../views/setup/banksetup/index.php');
    exit;
}
?>