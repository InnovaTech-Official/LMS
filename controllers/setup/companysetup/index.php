<?php
session_start();
require_once(__DIR__ . '/../../../includes/db_wrapper.php');

// Include model files
require_once(__DIR__ . '/../../../models/setup/companysetup/fetch.php');
require_once(__DIR__ . '/../../../models/setup/companysetup/update.php');
require_once(__DIR__ . '/../../../models/setup/companysetup/post.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../server/auth/login.php');
    exit;
}

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access denied. You are not authorized to view this page.";
    exit;
}

$success_message = '';
$error_message = '';
$company_data = null;

// Initialize database
$table_result = createOrUpdateTable($pdo);
if ($table_result !== true) {
    $error_message = $table_result;
}

// Get company data
$company_data = getCompanyData($pdo);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($company_data) {
        // Update existing company settings
        $result = updateCompanySettings($pdo, $_POST, $_FILES, $company_data);
    } else {
        // Create new company settings
        $result = createCompanySettings($pdo, $_POST, $_FILES);
    }
    
    if ($result['success']) {
        $success_message = $result['message'];
        // Refresh company data
        $company_data = getCompanyData($pdo);
    } else {
        $error_message = $result['message'];
    }
}

// Include the view file
require_once(__DIR__ . '/../../../views/setup/companysetup/index.php');