<?php
session_start();
require_once '../../../controllers/config/db_wrapper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

/**
 * Fetch all bank accounts from the database
 * 
 * @return array Array of bank account records
 */
function getAllBankAccounts() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM bank_info");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error fetching bank data: " . $e->getMessage();
        return [];
    }
}

/**
 * Fetch a single bank account by ID
 * 
 * @param int $id The ID of the bank account to fetch
 * @return array|null The bank account data or null if not found
 */
function getBankAccountById($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM bank_info WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error fetching bank account: " . $e->getMessage();
        return null;
    }
}

// Initialize the bank_info table if it doesn't exist
function initializeBankTable() {
    global $pdo;
    
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
        return true;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error creating table: " . $e->getMessage();
        return false;
    }
}