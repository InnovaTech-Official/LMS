<?php
require_once '../../../includes/db_wrapper.php';

function add_account_head($name) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($name)) {
            throw new Exception("Account head name is required");
        }
        
        // Check for duplicate
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts_head WHERE name = :name");
        $check_stmt->bindParam(':name', $name);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() > 0) {
            throw new Exception("Account head with this name already exists");
        }
        
        // Insert new account head
        $insert_stmt = $pdo->prepare("INSERT INTO accounts_head (name) VALUES (:name)");
        $insert_stmt->bindParam(':name', $name);
        
        if (!$insert_stmt->execute()) {
            throw new PDOException("Failed to add account head");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error adding account head: " . $e->getMessage());
        throw $e;
    }
}

function add_sub_account($name, $account_head_id) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($name) || empty($account_head_id)) {
            throw new Exception("Sub account name and account head are required");
        }
        
        // Check for duplicate
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM sub_accounts WHERE name = :name AND account_head_id = :account_head_id");
        $check_stmt->bindParam(':name', $name);
        $check_stmt->bindParam(':account_head_id', $account_head_id);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() > 0) {
            throw new Exception("Sub account with this name already exists under the selected account head");
        }
        
        // Insert new sub account
        $insert_stmt = $pdo->prepare("INSERT INTO sub_accounts (name, account_head_id) VALUES (:name, :account_head_id)");
        $insert_stmt->bindParam(':name', $name);
        $insert_stmt->bindParam(':account_head_id', $account_head_id);
        
        if (!$insert_stmt->execute()) {
            throw new PDOException("Failed to add sub account");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error adding sub account: " . $e->getMessage());
        throw $e;
    }
}

function add_account($name, $sub_account_id) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($name) || empty($sub_account_id)) {
            throw new Exception("Account name and sub account are required");
        }
        
        // Check for duplicate
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE name = :name AND sub_account_id = :sub_account_id");
        $check_stmt->bindParam(':name', $name);
        $check_stmt->bindParam(':sub_account_id', $sub_account_id);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() > 0) {
            throw new Exception("Account with this name already exists under the selected sub account");
        }
        
        // Insert new account
        $insert_stmt = $pdo->prepare("INSERT INTO accounts (name, sub_account_id) VALUES (:name, :sub_account_id)");
        $insert_stmt->bindParam(':name', $name);
        $insert_stmt->bindParam(':sub_account_id', $sub_account_id);
        
        if (!$insert_stmt->execute()) {
            throw new PDOException("Failed to add account");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error adding account: " . $e->getMessage());
        throw $e;
    }
}
?>