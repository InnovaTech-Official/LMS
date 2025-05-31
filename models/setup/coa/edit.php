<?php
require_once '../../../includes/db_wrapper.php';

function edit_item($id, $type, $name) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($id) || empty($type) || empty($name)) {
            throw new Exception("Edit ID, type and name are required");
        }
        
        // Update account head, sub account or account
        if ($type === 'account_head') {
            $update_stmt = $pdo->prepare("UPDATE accounts_head SET name = :name WHERE id = :id");
        } elseif ($type === 'sub_account') {
            $update_stmt = $pdo->prepare("UPDATE sub_accounts SET name = :name WHERE id = :id");
        } elseif ($type === 'account') {
            $update_stmt = $pdo->prepare("UPDATE accounts SET name = :name WHERE id = :id");
        } else {
            throw new Exception("Invalid edit type");
        }
        
        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':id', $id);
        
        if (!$update_stmt->execute()) {
            throw new PDOException("Failed to update item");
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Edit error: " . $e->getMessage());
        throw $e;
    }
}
?>