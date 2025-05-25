<?php
session_start();
require_once '../../../controllers/config/db_wrapper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Handle form submission for deleting bank info
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    
    $stmt = $pdo->prepare("DELETE FROM bank_info WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Bank information deleted successfully!";
    } else {
        $error_info = $stmt->errorInfo();
        $_SESSION['error_message'] = "Error deleting bank information: " . $error_info[2];
    }
    
    header('Location: ../../../views/setup/banksetup/index.php');
    exit;
}
?>