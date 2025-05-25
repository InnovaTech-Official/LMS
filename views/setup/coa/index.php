<?php
session_start();
require_once '../../../../server/config/db_wrapper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Initialize response array for AJAX requests
$response = array('success' => false, 'error' => '', 'has_dependencies' => false);

// Handle AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    try {
        if (isset($_POST['check_dependencies'])) {
            $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
            $delete_type = filter_var($_POST['delete_type'], FILTER_SANITIZE_STRING);
            
            if (!$delete_id || !$delete_type) {
                throw new Exception("Invalid input parameters");
            }
            
            $dependencies = check_dependencies($delete_id, $delete_type);
            
            $response['success'] = true;
            $response['has_dependencies'] = !empty($dependencies);
            if (!empty($dependencies)) {
                $forms_list = implode("', '", $dependencies);
                $response['error'] = "This item cannot be deleted because it is being used in: '$forms_list'. Please remove these entries first.";
            }
            
            echo json_encode($response);
            exit;
        }
        
        if (isset($_POST['delete_id']) && isset($_POST['delete_type'])) {
            $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
            $delete_type = filter_var($_POST['delete_type'], FILTER_SANITIZE_STRING);
            
            if (!$delete_id || !$delete_type) {
                throw new Exception("Invalid input parameters");
            }
            
            // Check for dependencies before deleting
            $dependencies = check_dependencies($delete_id, $delete_type);
            if (!empty($dependencies)) {
                throw new Exception("Cannot delete: Item has dependencies");
            }
            
            // If we get here, it's safe to delete
            if ($delete_type === 'account_head') {
                $delete_stmt = $pdo->prepare("DELETE FROM accounts_head WHERE id = :id");
            } elseif ($delete_type === 'sub_account') {
                $delete_stmt = $pdo->prepare("DELETE FROM sub_accounts WHERE id = :id");
            } elseif ($delete_type === 'account') {
                $delete_stmt = $pdo->prepare("DELETE FROM accounts WHERE id = :id");
            } else {
                throw new Exception("Invalid delete type");
            }
            
            $delete_stmt->bindParam(':id', $delete_id);
            if (!$delete_stmt->execute()) {
                throw new PDOException("Failed to delete item");
            }
            
            $response['success'] = true;
            $response['message'] = "Item deleted successfully";
            
            echo json_encode($response);
            exit;
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
        echo json_encode($response);
        exit;
    }
}

try {
    // Fetch account heads with proper error handling
    $account_heads_stmt = $pdo->prepare("SELECT * FROM accounts_head ORDER BY name");
    if (!$account_heads_stmt->execute()) {
        throw new PDOException("Failed to fetch account heads");
    }
    $account_heads = $account_heads_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch sub accounts with proper error handling
    $sub_accounts_stmt = $pdo->prepare("
        SELECT sa.*, sa.name as sub_name, ah.name as account_head_name 
        FROM sub_accounts sa 
        LEFT JOIN accounts_head ah ON sa.account_head_id = ah.id 
        ORDER BY ah.name, sa.name
    ");
    if (!$sub_accounts_stmt->execute()) {
        throw new PDOException("Failed to fetch sub accounts");
    }
    $sub_accounts = $sub_accounts_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch accounts with proper error handling
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
    $accounts = $accounts_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error in index.php: " . $e->getMessage());
    $error_message = "Failed to load account data. Please try again later.";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add_account_head'])) {
            // Validate input
            if (empty($_POST['name'])) {
                throw new Exception("Account head name is required");
            }
            
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            
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
            
            $response['success'] = true;
            $response['message'] = "Account head added successfully";
        } elseif (isset($_POST['add_sub_account'])) {
            // Validate input
            if (empty($_POST['name']) || empty($_POST['account_head_id'])) {
                throw new Exception("Sub account name and account head are required");
            }
            
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $account_head_id = filter_var($_POST['account_head_id'], FILTER_VALIDATE_INT);
            
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
            
            $response['success'] = true;
            $response['message'] = "Sub account added successfully";
        } elseif (isset($_POST['add_account'])) {
            // Validate input
            if (empty($_POST['name']) || empty($_POST['sub_account_id'])) {
                throw new Exception("Account name and sub account are required");
            }
            
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $sub_account_id = filter_var($_POST['sub_account_id'], FILTER_VALIDATE_INT);
            
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
            
            $response['success'] = true;
            $response['message'] = "Account added successfully";
        } elseif (isset($_POST['delete_id']) && isset($_POST['delete_type'])) {
            // Validate input
            if (empty($_POST['delete_id']) || empty($_POST['delete_type'])) {
                throw new Exception("Delete ID and type are required");
            }
            
            $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
            $delete_type = filter_var($_POST['delete_type'], FILTER_SANITIZE_STRING);
            
            // Check for dependencies
            $dependencies = check_dependencies($delete_id, $delete_type);
            
            if (!empty($dependencies)) {
                throw new Exception("There are entries associated with this Account in the following forms: '" . implode("', '", $dependencies) . "'. Please delete the associated entries from these forms first.");
            }
            
            // If we get here, it's safe to delete
            if ($delete_type === 'account_head') {
                $delete_stmt = $pdo->prepare("DELETE FROM accounts_head WHERE id = :id");
            } elseif ($delete_type === 'sub_account') {
                $delete_stmt = $pdo->prepare("DELETE FROM sub_accounts WHERE id = :id");
            } elseif ($delete_type === 'account') {
                $delete_stmt = $pdo->prepare("DELETE FROM accounts WHERE id = :id");
            }
            
            $delete_stmt->bindParam(':id', $delete_id);
            
            if (!$delete_stmt->execute()) {
                throw new PDOException("Failed to delete item");
            }
            
            $response['success'] = true;
            $response['message'] = ucfirst($delete_type) . " deleted successfully";
        } elseif (isset($_POST['edit_id']) && isset($_POST['edit_type']) && isset($_POST['edit_name'])) {
            // Validate input
            if (empty($_POST['edit_id']) || empty($_POST['edit_type']) || empty($_POST['edit_name'])) {
                throw new Exception("Edit ID, type and name are required");
            }
            
            $edit_id = filter_var($_POST['edit_id'], FILTER_VALIDATE_INT);
            $edit_type = filter_var($_POST['edit_type'], FILTER_SANITIZE_STRING);
            $edit_name = filter_var($_POST['edit_name'], FILTER_SANITIZE_STRING);
            
            // Update account head, sub account or account
            if ($edit_type === 'account_head') {
                $update_stmt = $pdo->prepare("UPDATE accounts_head SET name = :name WHERE id = :id");
            } elseif ($edit_type === 'sub_account') {
                $update_stmt = $pdo->prepare("UPDATE sub_accounts SET name = :name WHERE id = :id");
            } elseif ($edit_type === 'account') {
                $update_stmt = $pdo->prepare("UPDATE accounts SET name = :name WHERE id = :id");
            } else {
                throw new Exception("Invalid edit type");
            }
            
            $update_stmt->bindParam(':name', $edit_name);
            $update_stmt->bindParam(':id', $edit_id);
            
            if (!$update_stmt->execute()) {
                throw new PDOException("Failed to update account");
            }
            
            $response['success'] = true;
            $response['message'] = "Account updated successfully";
        } elseif (isset($_POST['check_dependencies'])) {
            $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
            $delete_type = filter_var($_POST['delete_type'], FILTER_SANITIZE_STRING);
            
            $dependencies = check_dependencies($delete_id, $delete_type);
            
            $response['has_dependencies'] = !empty($dependencies);
            if (!empty($dependencies)) {
                $forms_list = implode("', '", $dependencies);
                $response['error'] = "There are entries associated with this Account in the following forms: '$forms_list'. Please delete the associated entries from these forms first.";
            }
        }
        
    } catch (Exception $e) {
        error_log("Error in index.php: " . $e->getMessage());
        $response['error'] = $e->getMessage();
    }
    
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Redirect for non-AJAX requests
    header('Location: index.php' . ($response['success'] ? '?success=1' : '?error=' . urlencode($response['error'])));
    exit;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Chart of Accounts</title>
    <link rel="stylesheet" href="../../../assets/css/coa.css">
</head>
<body>
    <a href="../../dashboard/pos_dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    <h1>Setup Chart of Accounts</h1>

    <div class="form-container">
        <h2>Add Account Head</h2>
        <form method="POST" action="index.php">
            <label for="name">Account Head Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_account_head">Add Account Head</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Sub-Account</h2>
        <form method="POST" action="index.php">
            <label for="account_head_id">Select Account Head:</label>
            <select id="account_head_id" name="account_head_id" required>
                <?php foreach ($account_heads as $head): ?>
                    <option value="<?= htmlspecialchars($head['id']) ?>"><?= htmlspecialchars($head['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="name">Sub-Account Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_sub_account">Add Sub-Account</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Account</h2>
        <form method="POST" action="index.php">
            <label for="sub_account_id">Select Sub-Account:</label>
            <select id="sub_account_id" name="sub_account_id" required>
                <?php foreach ($sub_accounts as $sub_account): ?>
                    <option value="<?= htmlspecialchars($sub_account['id']) ?>"><?= htmlspecialchars($sub_account['sub_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="name">Account Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_account">Add Account</button>
        </form>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Account Heads</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchHeads" class="search-input" placeholder="Search account heads...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Heads')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Heads')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="accountHeadsList">
            <?php foreach ($account_heads as $head): ?>
                <li>
                    <?= htmlspecialchars($head['name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($head['id']) ?>', 'account_head', '<?= htmlspecialchars($head['name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($head['id']) ?>', 'account_head', '<?= htmlspecialchars($head['name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Sub-Accounts</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchSubs" class="search-input" placeholder="Search sub-accounts...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Subs')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Subs')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="subAccountsList">
            <?php foreach ($sub_accounts as $sub_account): ?>
                <li>
                    <?= htmlspecialchars($sub_account['account_head_name']) ?> - <?= htmlspecialchars($sub_account['sub_name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($sub_account['id']) ?>', 'sub_account', '<?= htmlspecialchars($sub_account['sub_name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($sub_account['id']) ?>', 'sub_account', '<?= htmlspecialchars($sub_account['sub_name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Accounts</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchAccounts" class="search-input" placeholder="Search accounts...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Accounts')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Accounts')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="accountsList">
            <?php foreach ($accounts as $account): ?>
                <li>
                    <?= htmlspecialchars($account['account_head_name']) ?> - <?= htmlspecialchars($account['sub_account_name']) ?> - <?= htmlspecialchars($account['name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($account['id']) ?>', 'account', '<?= htmlspecialchars($account['name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($account['id']) ?>', 'account', '<?= htmlspecialchars($account['name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <h2>Edit</h2>
            <form method="POST" action="index.php">
                <input type="hidden" id="edit_id" name="edit_id">
                <input type="hidden" id="edit_type" name="edit_type">
                <label for="edit_name">Name:</label>
                <input type="text" id="edit_name" name="edit_name" required>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
            <h2>Delete Confirmation</h2>
            <p id="deleteConfirmationText"></p>
            <div class="modal-buttons">
                <button id="cancelDeleteBtn" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button id="confirmDeleteBtn" class="btn-confirm-delete" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/coa.js"></script>
</body>
</html>