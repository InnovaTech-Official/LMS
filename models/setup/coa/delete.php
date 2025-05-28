<?php
require_once '../../../includes/db_wrapper.php';
require_once __DIR__ . '/fetch.php';

function delete_item($id, $type) {
    global $pdo;
    
    try {
        // Check for dependencies before deleting
        $dependencies = check_dependencies($id, $type);
        if (!empty($dependencies)) {
            throw new Exception("There are entries associated with this item in the following: '" . implode("', '", $dependencies) . "'. Please delete the associated entries first.");
        }
        
        // If we get here, it's safe to delete
        if ($type === 'account_head') {
            $delete_stmt = $pdo->prepare("DELETE FROM accounts_head WHERE id = :id");
        } elseif ($type === 'sub_account') {
            $delete_stmt = $pdo->prepare("DELETE FROM sub_accounts WHERE id = :id");
        } elseif ($type === 'account') {
            $delete_stmt = $pdo->prepare("DELETE FROM accounts WHERE id = :id");
        } else {
            throw new Exception("Invalid delete type");
        }
        
        $delete_stmt->bindParam(':id', $id);
        if (!$delete_stmt->execute()) {
            throw new PDOException("Failed to delete item");
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        throw $e;
    }
}
?>