<?php
session_start();
require_once '../../../includes/db_wrapper.php';
require_once '../../../models/setup/coa/fetch.php';
require_once '../../../models/setup/coa/post.php';
require_once '../../../models/setup/coa/edit.php';
require_once '../../../models/setup/coa/delete.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Initialize response array for AJAX requests
$response = array('success' => false, 'error' => '', 'has_dependencies' => false);

// Initialize variables for the view
$account_heads = [];
$sub_accounts = [];
$accounts = [];
$error_message = '';

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
            
            // Use the model function to delete the item
            delete_item($delete_id, $delete_type);
            
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

// Get data from models for the view
try {
    $account_heads = fetch_account_heads();
    $sub_accounts = fetch_sub_accounts();
    $accounts = fetch_accounts();
} catch (PDOException $e) {
    error_log("Database error in index.php: " . $e->getMessage());
    $error_message = "Failed to load account data. Please try again later.";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add_account_head'])) {
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            add_account_head($name);
            $response['success'] = true;
            $response['message'] = "Account head added successfully";
        } 
        elseif (isset($_POST['add_sub_account'])) {
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $account_head_id = filter_var($_POST['account_head_id'], FILTER_VALIDATE_INT);
            add_sub_account($name, $account_head_id);
            $response['success'] = true;
            $response['message'] = "Sub account added successfully";
        } 
        elseif (isset($_POST['add_account'])) {
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $sub_account_id = filter_var($_POST['sub_account_id'], FILTER_VALIDATE_INT);
            add_account($name, $sub_account_id);
            $response['success'] = true;
            $response['message'] = "Account added successfully";
        } 
        elseif (isset($_POST['edit_id']) && isset($_POST['edit_type']) && isset($_POST['edit_name'])) {
            $edit_id = filter_var($_POST['edit_id'], FILTER_VALIDATE_INT);
            $edit_type = filter_var($_POST['edit_type'], FILTER_SANITIZE_STRING);
            $edit_name = filter_var($_POST['edit_name'], FILTER_SANITIZE_STRING);
            
            edit_item($edit_id, $edit_type, $edit_name);
            $response['success'] = true;
            $response['message'] = "Item updated successfully";
        } 
        elseif (isset($_POST['check_dependencies'])) {
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

// Include the view
include '../../../views/setup/coa/index.php';
?>