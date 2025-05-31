<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login controller if not logged in
    header('Location: ../auth/login.php');
    exit();
}

// Include any necessary models or database connections
require_once('../../includes/db_wrapper.php');
require_once('../../includes/permissions.php');

// Define navigation items
$navItems = [
    'dashboard.php' => ['Dashboard', 'fa-solid fa-gauge-high'],
    'setup.php' => ['Setup', 'fa-solid fa-cog'],
    'entry.php' => ['Entry', 'fa-solid fa-edit'],
    'hr.php' => ['HR', 'fa-solid fa-users-gear'],
    'reports.php' => ['Reports', 'fa-solid fa-file-lines']
];

// Set current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Define POS items with updated icon classes that work with Font Awesome 6
$pos_items = [
    'pos_sale' => [
        'name' => 'Loan Dispatch',
        'description' => 'Process loan disbursements',
        'icon' => 'fa-solid fa-money-bill-transfer',
        'color' => '#4361ee', // Blue
        'link' => 'loan_dispatch.php',
        'primary' => true
    ],
    'daily_sales' => [
        'name' => 'Daily Report',
        'description' => 'View today\'s loan activity',
        'icon' => 'fa-solid fa-chart-column',
        'color' => '#2ec4b6', // Teal
        'link' => 'daily_report.php'
    ],
    'customer_lookup' => [
        'name' => 'Borrower Lookup',
        'description' => 'Search borrower information',
        'icon' => 'fa-solid fa-user-group',
        'color' => '#ff9f1c', // Orange
        'link' => 'borrower_lookup.php'
    ],
    'products' => [
        'name' => 'Pending Applications',
        'description' => 'Review loan applications',
        'icon' => 'fa-solid fa-file-invoice',
        'color' => '#38b000', // Green
        'link' => 'pending_applications.php'
    ],
    'receive_payment' => [
        'name' => 'Receive Payment',
        'description' => 'Process loan repayments',
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'color' => '#e63946', // Red
        'link' => 'receive_payment.php'
    ],
    'cashbook' => [
        'name' => 'Cashbook',
        'description' => 'Track cash transactions',
        'icon' => 'fa-solid fa-cash-register',
        'color' => '#7209b7', // Purple
        'link' => 'cashbook.php'
    ],
    'ledger' => [
        'name' => 'Ledger',
        'description' => 'View accounting records',
        'icon' => 'fa-solid fa-book-open',
        'color' => '#212529', // Dark Gray
        'link' => 'ledger.php'
    ],
    'other_reports' => [
        'name' => 'Other Reports',
        'description' => 'Access additional reports',
        'icon' => 'fa-solid fa-file-lines',
        'color' => '#457b9d', // Steel Blue
        'link' => 'other_reports.php'
    ]
];

// Get user role information
$role_id = $_SESSION['role_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;
$username = $_SESSION['username'] ?? 'User';

// Include the dashboard view
include('../../views/dashboard/dashboard.php');
?>
