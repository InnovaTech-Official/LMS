<?php
require_once __DIR__ . '/../../../models/setup/banksetup/fetch.php';

// Function to delete a bank account
function deleteBankAccount($id) {
    global $pdo;
    
    // Prepare and execute query
    $sql = "DELETE FROM bank_info WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    // Execute query
    if ($stmt->execute([$id])) {
        $pdo = null;
        return true;
    } else {
        $error = $stmt->errorInfo()[2];
        $pdo = null;
        return $error;
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && $_POST['delete'] == "1") {
    $id = isset($_POST['delete_id']) ? $_POST['delete_id'] : '';
    
    if (!empty($id)) {
        $result = deleteBankAccount($id);
        
        // Redirect with appropriate message
        if ($result === true) {
            header("Location: ../../../views/setup/banksetup/index.php?success=Account deleted successfully");
        } else {
            header("Location: ../../../views/setup/banksetup/index.php?error=" . urlencode($result));
        }
    } else {
        header("Location: ../../../views/setup/banksetup/index.php?error=Invalid bank account ID");
    }
    exit();
}
?>