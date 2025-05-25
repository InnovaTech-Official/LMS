<?php
session_start();
include '../../../server/config/db_wrapper.php';
include '../../../server/config/permissions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../server/auth/login.php');
    exit;
}

$role_id = $_SESSION['role_id'];

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

// Define setup items
$setup_items = [
    'company_setup' => [
        'name' => 'Company Setup',
        'description' => 'Configure company profile, contact information, and business settings',
        'icon' => 'fas fa-building fa-3x',
        'color' => '#3b82f6',
        'link' => '../setup/companysetup/index.php'
    ],
    'chart_of_accounts' => [
        'name' => 'Chart of Accounts',
        'description' => 'Set up and organize financial accounts hierarchy and structure',
        'icon' => 'fas fa-sitemap',
        'link' => '../setup/coa/index.php'
    ],
    'bank_setup' => [
        'name' => 'Bank Setup',
        'description' => 'Manage bank accounts, branches, and banking information',
        'icon' => 'fas fa-university',
        'link' => '../setup/banksetup/index.php'
    ],
    'customer_setup' => [
        'name' => 'Customer Setup',
        'description' => 'Add and manage customer profiles, credit limits, and contacts',
        'icon' => 'fas fa-users',
        'link' => '../setup/customersetup/index.php'
    ],
    'vendor_setup' => [
        'name' => 'Vendor Setup',
        'description' => 'Manage supplier information, payment terms, and vendor categories',
        'icon' => 'fas fa-truck',
        'link' => '../setup/vendorsetup/index.php'
    ],
    'item_setup' => [
        'name' => 'Item Setup',
        'description' => 'Configure products, pricing, and inventory management settings',
        'icon' => 'fas fa-box',
        'link' => '../setup/itemsetup/'
    ],
    'item_category' => [
        'name' => 'Item Category Setup',
        'description' => 'Organize products into main categories and classifications',
        'icon' => 'fas fa-tags',
        'link' => '../setup/itemcategory/index.php'
    ],
    'item_subcategory' => [
        'name' => 'Item Sub Category Setup',
        'description' => 'Define detailed product subcategories and groupings',
        'icon' => 'fas fa-tag',
        'link' => '../setup/itemsubcategory/index.php'
    ],
    'zone_setup' => [
        'name' => 'Zone Setup',
        'description' => 'Configure geographical zones and delivery regions',
        'icon' => 'fas fa-map-marked-alt',
        'link' => '../setup/zonesetup/index.php'
    ],
    'transport_setup' => [
        'name' => 'Transport Setup',
        'description' => 'Manage vehicles, routes, and transportation settings',
        'icon' => 'fas fa-truck-moving',
        'link' => '../setup/transportsetup/index.php'
    ],
    'warehouse_setup' => [
        'name' => 'Warehouse Setup',
        'description' => 'Configure storage locations, racks, and warehouse management',
        'icon' => 'fas fa-warehouse',
        'link' => '../setup/warehousesetup/index.php'
    ],
    'batch_setup' => [
        'name' => 'Batch Setup',
        'description' => 'Manage product batches, expiry dates, and batch tracking',
        'icon' => 'fas fa-boxes',
        'link' => '../setup/batchsetup/index.php'
    ]
];

// Add helper function to ensure icons have appropriate size class
function ensureIconSize($iconClass) {
    if (strpos($iconClass, 'fa-') !== false && strpos($iconClass, 'fa-') === 0) {
        // If it starts with fa- prefix but has no size, add fa-3x
        return $iconClass . ' fa-3x';
    } else if (strpos($iconClass, 'fa-3x') === false && 
               strpos($iconClass, 'fa-2x') === false && 
               strpos($iconClass, 'fa-lg') === false) {
        // If no size class is present, add fa-3x
        return $iconClass . ' fa-3x';
    }
    
    return $iconClass;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
    'pos_dashboard.php' => ['POS', 'fas fa-cash-register'],
    'setup.php' => ['Setup', 'fas fa-cog'],
    'entry.php' => ['Entry', 'fas fa-edit'],
    'reports.php' => ['Reports', 'fas fa-file-alt']
];

// Include the view file
include '../../views/dashboard/setup.html';
?>