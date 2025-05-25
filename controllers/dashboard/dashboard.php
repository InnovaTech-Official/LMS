<?php
session_start();
require_once (__DIR__ . '/../../server/config/db_wrapper.php');
require_once (__DIR__ . '/../../server/config/permissions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../server/auth/login.php');
    exit;
}

$role_id = $_SESSION['role_id'];
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Define POS items with large icons for easy access
$pos_items = [
    'pos_sale' => [
        'name' => 'New Sales Bill',
        'description' => 'Create a new POS sales transaction',
        'icon' => 'fas fa-cash-register fa-3x',
        'color' => '#22c55e',
        'link' => '../entry/pos/pos_bill.php',
        'primary' => true
    ],
    'daily_sales' => [
        'name' => 'Today\'s Sales',
        'description' => 'View transactions from today',
        'icon' => 'fas fa-calendar-day fa-3x',
        'color' => '#3b82f6',
        'link' => '../report/dailysalereport/'
    ],
    'customer_lookup' => [
        'name' => 'Customer Lookup',
        'description' => 'Search for customer information',
        'icon' => 'fas fa-users fa-3x',
        'color' => '#f59e0b',
        'link' => '../setup/customersetup/index.php'
    ],
    'products' => [
        'name' => 'Products',
        'description' => 'Search and manage product inventory',
        'icon' => 'fas fa-box-open fa-3x',
        'color' => '#6366f1',
        'link' => '../setup/itemsetup/'
    ],
    'receive_payment' => [
        'name' => 'Receive Payment',
        'description' => 'Process customer payments',
        'icon' => 'fas fa-hand-holding-usd fa-3x',
        'color' => '#ec4899',
        'link' => '../accounts/vouchers/receive_voucher2/receive_voucher.php'
    ],
    'cashbook' => [
        'name' => 'CashBook',
        'description' => 'View and manage cash transactions',
        'icon' => 'fas fa-money-bill fa-3x',
        'color' => '#0891b2',
        'link' => '../report/cashbook/',
    ],
    'ledger' => [
        'name' => 'Customer Ledger',
        'description' => 'View customer transaction history',
        'icon' => 'fas fa-book fa-3x',
        'color' => '#8b5cf6',
        'link' => '../report/indivisual_customer_ledger/'
    ],
    'stock' => [
        'name' => 'Stock Report',
        'description' => 'View stock levels and reports',
        'icon' => 'fas fa-arrow-trend-up fa-3x',
        'color' => '#64748b',
        'link' => '../report/stock_report/'
    ]
];

$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
    'pos_dashboard.php' => ['POS', 'fas fa-cash-register'],
    'setup.php' => ['Setup', 'fas fa-cog'],
    'entry.php' => ['Entry', 'fas fa-edit'],
    'reports.php' => ['Reports', 'fas fa-file-alt']
];

// Process permissions for POS items
$filtered_pos_items = [];
foreach ($pos_items as $key => $item) {
    if ($key == 'pos_sale' || 
        $key == 'daily_sales' || 
        $key == 'customer_lookup' || 
        $key == 'products' || 
        $key == 'receive_payment' || 
        $key == 'cashbook' || 
        $key == 'ledger' || 
        $key == 'stock' || 
        hasPermission($pdo, $role_id, 'POS', $key)) {
        $filtered_pos_items[$key] = $item;
    }
}

// Pass variables to view
$viewData = [
    'navItems' => $navItems,
    'currentPage' => $currentPage,
    'is_admin' => $is_admin,
    'pos_items' => $filtered_pos_items
];
extract($viewData);

require_once(__DIR__ . '/../../views/dashboard/dashboard.html');