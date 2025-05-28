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

// Get user role information
$role_id = $_SESSION['role_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;
$username = $_SESSION['username'] ?? 'User';

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

// Define report items with updated icon classes for Font Awesome 6
$report_items = [
    'loan_portfolio' => [
        'name' => 'Loan Portfolio',
        'description' => 'View complete loan portfolio and status',
        'icon' => 'fa-solid fa-sack-dollar',
        'color' => '#4361ee', // Blue
        'link' => '../../views/reports/loan_portfolio.php',
        'highlight' => true
    ],
    'borrower_report' => [
        'name' => 'Borrower Report',
        'description' => 'View borrower information and history',
        'icon' => 'fa-solid fa-users',
        'color' => '#f59e0b', // Orange
        'link' => '../../views/reports/borrower_report.php'
    ],
    'transaction_history' => [
        'name' => 'Transaction History',
        'description' => 'View complete transaction records',
        'icon' => 'fa-solid fa-receipt',
        'color' => '#2ec4b6', // Teal
        'link' => '../../views/reports/transaction_history.php'
    ],
    'loan_aging' => [
        'name' => 'Loan Aging',
        'description' => 'View overdue loans by aging periods',
        'icon' => 'fa-solid fa-hourglass-half',
        'color' => '#ef4444', // Red
        'link' => '../../views/reports/loan_aging.php'
    ],
    'repayment_schedule' => [
        'name' => 'Repayment Schedule',
        'description' => 'View upcoming loan repayments',
        'icon' => 'fa-solid fa-calendar-days',
        'color' => '#38b000', // Green
        'link' => '../../views/reports/repayment_schedule.php'
    ],
    'collection_report' => [
        'name' => 'Collection Report',
        'description' => 'View loan collection performance',
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'color' => '#7209b7', // Purple
        'link' => '../../views/reports/collection_report.php'
    ],
    'financial_statement' => [
        'name' => 'Financial Statements',
        'description' => 'View balance sheet and income statements',
        'icon' => 'fa-solid fa-chart-pie',
        'color' => '#0891b2', // Cyan
        'link' => '../../views/reports/financial_statement.php'
    ],
    'loan_officer' => [
        'name' => 'Loan Officer Report',
        'description' => 'View performance by loan officer',
        'icon' => 'fa-solid fa-user-tie',
        'color' => '#6366f1', // Indigo
        'link' => '../../views/reports/loan_officer_report.php'
    ],
    'branch_performance' => [
        'name' => 'Branch Performance',
        'description' => 'View performance metrics by branch',
        'icon' => 'fa-solid fa-building',
        'color' => '#64748b', // Slate
        'link' => '../../views/reports/branch_performance.php'
    ]
];

// Include the reports view
include('../../views/dashboard/reports.php');
?>