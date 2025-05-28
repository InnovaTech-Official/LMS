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

// Define entry items with updated icon classes for Font Awesome 6
$entry_items = [
    'pos_bill' => [
        'name' => 'Loan Disbursement',
        'description' => 'Process loan disbursement transactions',
        'icon' => 'fa-solid fa-money-bill-transfer',
        'color' => '#4361ee', // Blue
        'link' => '../../views/entry/pos/pos_bill.php',
        'highlight' => true  // Add highlight flag for loan disbursement
    ],
    'borrower_entry' => [
        'name' => 'Borrower Entry',
        'description' => 'Add and manage borrower profiles and information',
        'icon' => 'fa-solid fa-users',
        'color' => '#f59e0b', // Orange
        'link' => '../../views/setup/customersetup/index.php'
    ],
    'sale_return' => [
        'name' => 'Pending Applications',
        'description' => 'Review and process loan applications',
        'icon' => 'fa-solid fa-file-invoice',
        'color' => '#ef4444', // Red
        'link' => '../../views/entry/sale_return/'
    ],
    'receive_voucher' => [
        'name' => 'Receive Payment',
        'description' => 'Process incoming loan repayments',
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'color' => '#0891b2', // Cyan
        'link' => '../../views/accounts/vouchers/receive_voucher/receive_voucher.php'
    ],
    'receive_voucher2' => [
        'name' => 'Bulk Repayments',
        'description' => 'Process multiple loan repayments',
        'icon' => 'fa-solid fa-money-bills',
        'color' => '#8b5cf6', // Purple
        'link' => '../../views/accounts/vouchers/receive_voucher2/'
    ],
    'payment_voucher' => [
        'name' => 'Payment Voucher',
        'description' => 'Record and track outgoing payments',
        'icon' => 'fa-solid fa-receipt',
        'color' => '#ec4899', // Pink
        'link' => '../../views/accounts/vouchers/payment_voucher/payment_voucher.php'
    ],
    'journal_voucher' => [
        'name' => 'Journal Voucher',
        'description' => 'Manage journal entries and adjustments',
        'icon' => 'fa-solid fa-book-open',
        'color' => '#64748b', // Slate
        'link' => '../../views/accounts/vouchers/journal_voucher/journal_voucher.php'
    ],
    'expense_voucher' => [
        'name' => 'Expense Voucher',
        'description' => 'Track and record business expenses',
        'icon' => 'fa-solid fa-file-invoice-dollar',
        'color' => '#6366f1', // Indigo
        'link' => '../../views/accounts/vouchers/expense_voucher/expns.php'
    ]
];

// Include the entry view
include('../../views/dashboard/entry.php');
?>