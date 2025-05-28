<?php
require_once '../../../includes/db_wrapper.php';

function fetch_account_heads() {
    global $pdo;
    
    try {
        $account_heads_stmt = $pdo->prepare("SELECT * FROM accounts_head ORDER BY name");
        if (!$account_heads_stmt->execute()) {
            throw new PDOException("Failed to fetch account heads");
        }
        return $account_heads_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching account heads: " . $e->getMessage());
        throw $e;
    }
}

function fetch_sub_accounts() {
    global $pdo;
    
    try {
        $sub_accounts_stmt = $pdo->prepare("
            SELECT sa.*, sa.name as sub_name, ah.name as account_head_name 
            FROM sub_accounts sa 
            LEFT JOIN accounts_head ah ON sa.account_head_id = ah.id 
            ORDER BY ah.name, sa.name
        ");
        if (!$sub_accounts_stmt->execute()) {
            throw new PDOException("Failed to fetch sub accounts");
        }
        return $sub_accounts_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching sub accounts: " . $e->getMessage());
        throw $e;
    }
}

function fetch_accounts() {
    global $pdo;
    
    try {
        $accounts_stmt = $pdo->prepare("
            SELECT a.*, sa.name as sub_account_name, ah.name as account_head_name
            FROM accounts a
            LEFT JOIN sub_accounts sa ON a.sub_account_id = sa.id
            LEFT JOIN accounts_head ah ON sa.account_head_id = ah.id
            ORDER BY ah.name, sa.name, a.name
        ");
        if (!$accounts_stmt->execute()) {
            throw new PDOException("Failed to fetch accounts");
        }
        return $accounts_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching accounts: " . $e->getMessage());
        throw $e;
    }
}

function check_dependencies($id, $type) {
    global $pdo;
    $dependencies = array();
    
    try {
        if ($type === 'account_head') {
            // Check if account head has any sub accounts
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM sub_accounts WHERE account_head_id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();
            if ($check_stmt->fetchColumn() > 0) {
                $dependencies[] = 'Sub Accounts';
            }
        } elseif ($type === 'sub_account') {
            // Check if sub account has any accounts
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE sub_account_id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();
            if ($check_stmt->fetchColumn() > 0) {
                $dependencies[] = 'Accounts';
            }
        } elseif ($type === 'account') {
            // First check if there are any journal entries referencing this account
            $tables_to_check = [
                'journal_entries' => ['debit_account_id', 'credit_account_id'],
                'payment_voucher_entries' => ['debit_account_id', 'credit_account_id'],
                'receive_voucher_entries' => ['debit_account_id', 'credit_account_id'],
                // Add any other tables that might reference accounts
            ];
            
            foreach ($tables_to_check as $table => $columns) {
                foreach ($columns as $column) {
                    // Check if table exists before querying
                    $table_exists = $pdo->prepare("SHOW TABLES LIKE :table");
                    $table_exists->execute(['table' => $table]);
                    
                    if ($table_exists->rowCount() > 0) {
                        // If table exists, check for dependencies
                        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = :id");
                        $check_stmt->bindParam(':id', $id);
                        $check_stmt->execute();
                        
                        if ($check_stmt->fetchColumn() > 0) {
                            // Convert table name to more readable format
                            $readable_name = ucwords(str_replace('_', ' ', $table));
                            $dependencies[] = $readable_name;
                            break; // Break inner loop if dependency found in this table
                        }
                    }
                }
            }
        }
        
        return $dependencies;
        
    } catch (Exception $e) {
        error_log("Error checking dependencies: " . $e->getMessage());
        throw new Exception("Error checking dependencies: " . $e->getMessage());
    }
}
?>