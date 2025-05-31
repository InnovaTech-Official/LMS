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

function getRandomColor($key) {
    $colors = [
        '#3b82f6', '#22c55e', '#ef4444', '#f59e0b', '#8b5cf6',
        '#ec4899', '#0891b2', '#64748b', '#84cc16', '#14b8a6',
        '#6366f1', '#d946ef', '#f43f5e', '#0ea5e9'
    ];
    $hash = 0;
    for ($i = 0; $i < strlen($key); $i++) {
        $hash = ord($key[$i]) + (($hash << 5) - $hash);
    }
    $index = abs($hash) % count($colors);
    return $colors[$index];
}

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

// Define setup items with updated icon classes for Font Awesome 6
$setup_items = [
    'company_setup' => [
        'name' => 'Company Setup',
        'description' => 'Configure company profile, contact information, and business settings',
        'icon' => 'fa-solid fa-building',
        'color' => '#3b82f6', // Blue
        'link' => '../setup/companysetup/'
    ],
    'chart_of_accounts' => [
        'name' => 'Chart of Accounts',
        'description' => 'Set up and organize financial accounts hierarchy and structure',
        'icon' => 'fa-solid fa-sitemap',
        'color' => '#22c55e', // Green
        'link' => '../setup/coa/'
    ],
    'bank_setup' => [
        'name' => 'Bank Setup',
        'description' => 'Manage bank accounts, branches, and banking information',
        'icon' => 'fa-solid fa-university',
        'color' => '#ef4444', // Red
        'link' => '../../views/setup/banksetup/'
    ],
    'item_setup' => [
        'name' => 'Loan Types',
        'description' => 'Configure loan products, pricing, and terms',
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'color' => '#ec4899', // Pink
        'link' => '../../views/setup/itemsetup/index.php'
    ],
    'penalty_setup' => [
        'name' => 'Penalty Setup',
        'description' => 'Configure late payment penalties and fee structures',
        'icon' => 'fa-solid fa-exclamation-circle',
        'color' => '#dc2626', // Bright Red
        'link' => '../../views/setup/penaltysetup/index.php'
    ],
    'area_setup' => [
        'name' => 'Area Setup',
        'description' => 'Configure geographical areas and service regions',
        'icon' => 'fa-solid fa-map-location-dot',
        'color' => '#84cc16', // Lime
        'link' => '../setup/areasetup/'
    ],
    'warehouse_setup' => [
        'name' => 'Branch Setup',
        'description' => 'Configure branch locations and management',
        'icon' => 'fa-solid fa-building-user',
        'color' => '#6366f1', // Indigo
        'link' => '../../views/setup/warehousesetup/index.php'
    ]
];

// Include the setup view
include('../../views/dashboard/setup.php');
?>