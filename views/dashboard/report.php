<?php
session_start();
include '../../../server/config/db_wrapper.php';
include '../../../server/config/permissions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../server/auth/login.php');
    exit;
}

$role_id = $_SESSION['role_id'];

// Define report items
$report_items = [
    'recovery_sheet' => [
        'name' => 'Recovery Sheet',
        'description' => 'Track and manage account recoveries and collections',
        'icon' => 'fas fa-file-invoice fa-3x',
        'link' => '../report/recovery_sheet/'
    ],
    'daily_sale' => [
        'name' => 'Daily Sale Report',
        'description' => 'View daily sales performance and statistics',
        'icon' => 'fas fa-chart-line fa-3x',
        'link' => '../report/dailysalereport/'
    ],
    'sale_report' => [
        'name' => 'Sale Report',
        'description' => 'Analyze sales trends and performance metrics',
        'icon' => 'fas fa-chart-bar fa-3x',
        'link' => '../report/sale_report/'
    ],
    'purchase_report' => [
        'name' => 'Purchase Report',
        'description' => 'Track purchase history and supplier transactions',
        'icon' => 'fas fa-shopping-basket fa-3x',
        'link' => '../report/purchase_report/'
    ],
    'stock_report' => [
        'name' => 'Stock Report',
        'description' => 'Monitor inventory levels and stock movements',
        'icon' => 'fas fa-boxes fa-3x',
        'link' => '../report/stock_report/'
    ],
    'customer_ledger' => [
        'name' => 'Customer Ledger Report',
        'description' => 'View customer account statements and balances',
        'icon' => 'fas fa-book-reader fa-3x',
        'link' => '../report/all_customer_ledger/'
    ],
    'customer_individual' => [
        'name' => 'Customer Individual Ledger',
        'description' => 'Detailed transaction history for individual customers',
        'icon' => 'fas fa-user-clock fa-3x',
        'link' => '../report/indivisual_customer_ledger/'
    ],
    'vendor_ledger' => [
        'name' => 'Vendor Ledger Report',
        'description' => 'Track vendor account statements and payments',
        'icon' => 'fas fa-truck fa-3x',
        'link' => '../report/all_vendor_ledger/'
    ],
    'vendor_individual' => [
        'name' => 'Vendor Individual Ledger',
        'description' => 'Detailed transaction history for individual vendors',
        'icon' => 'fas fa-user-tie fa-3x',
        'link' => '../report/indivisual_vendor_ledger/'
    ],
    'cashbook' => [
        'name' => 'Cashbook',
        'description' => 'Monitor cash inflows and outflows',
        'icon' => 'fas fa-cash-register fa-3x',
        'link' => '../report/cashbook/'
    ],
    'income_statement' => [
        'name' => 'Income Statement',
        'description' => 'View profit and loss statement',
        'icon' => 'fas fa-file-invoice-dollar fa-3x',
        'link' => '../report/profit-and-loss/'
    ],
    'trial_balance' => [
        'name' => 'Trial Balance',
        'description' => 'Check account balances and reconciliation',
        'icon' => 'fas fa-balance-scale fa-3x',
        'link' => '../report/trial_balance.php'
    ],
    'customer_aging' => [
        'name' => 'Customer Aging',
        'description' => 'Analyze customer payment trends and dues',
        'icon' => 'fas fa-clock fa-3x',
        'link' => '../report/customer_aging/'
    ],
    'vendor_aging' => [
        'name' => 'Vendor Aging',
        'description' => 'Track seller payment history and dues',
        'icon' => 'fas fa-user-clock fa-3x',
        'link' => '../report/vendor_aging/'
    ],
    'balance_sheet' => [
        'name' => 'Balance Sheet',
        'description' => 'View company assets, liabilities, and equity',
        'icon' => 'fas fa-file-alt fa-3x',
        'link' => '../report/balance_sheet.php'
    ],
    'short_item_list' => [
        'name' => 'Short Item List',
        'description' => 'Quick overview of items with low stock levels',
        'icon' => 'fas fa-list-alt fa-3x',
        'link' => '../report/short_item_list/index.html'
    ],
    'profit_and_loss' => [
        'name' => 'Short Item List',
        'description' => 'Quick overview of items with low stock levels',
        'icon' => 'fas fa-list-alt fa-3x',
        'link' => '../report/Profit-and-loss/'
    ],
    'daybook' => [
        'name' => 'DayBook',
        'description' => 'Quick overview of items with low stock levels',
        'icon' => 'fas fa-list-alt fa-3x',
        'link' => '../report/daybook/'
    ]
];

// Function to display all reports if permissions system isn't fully implemented
function showAllReports($pdo, $role_id, $module, $permission) {
    // If the permissions system is implemented, use it
    if (function_exists('hasPermission')) {
        $result = hasPermission($pdo, $role_id, $module, $permission);
        return $result;
    }
    
    // Otherwise show all reports (fallback)
    return true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f172a;
            --secondary: #1e293b;
            --accent: #3b82f6;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--light);
            min-height: 100vh;
        }

        .top-navbar {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .top-navbar .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        .top-navbar .nav-brand h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .top-navbar .nav-menu {
            list-style: none;
            display: flex;
            gap: 1rem;
        }

        .top-navbar .nav-menu li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease;
        }

        .top-navbar .nav-menu li a:hover {
            background: var(--accent);
        }

        .nav-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
            padding: 0.75rem 1.25rem;
            color: var(--light);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 500;
            justify-content: center;
        }

        .nav-menu .nav-link i {
            font-size: 1.1rem;
        }

        .nav-menu .nav-link.active {
            background: var(--accent);
            color: white;
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: var(--light);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid var(--light);
        }

        .nav-btn:hover {
            background: var(--light);
            color: var(--primary);
        }

        .nav-btn.admin {
            background: var(--accent);
            border-color: var(--accent);
        }

        .nav-btn.admin:hover {
            background: #2563eb;
            color: var(--light);
        }

        .content-wrapper {
            padding: 6rem 2rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Clock and date styles */
        .date-time-container {
            text-align: center;
            margin-bottom: 2rem;
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .clock-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .date-display {
            font-size: 1.25rem;
            color: #64748b;
            font-weight: 500;
        }

        .report-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .report-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 250px;
            justify-content: center;
            height: 100%;
        }

        .report-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .report-card i {
            margin-bottom: 1.5rem;
        }

        .report-card h2 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .report-card p {
            color: #64748b;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
        }

        .icon-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            color: white;
        }

        .report-card:hover .icon-circle {
            transform: scale(1.1);
        }

        @media (max-width: 1400px) {
            .report-cards {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .report-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .report-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .clock-display {
                font-size: 2rem;
            }
            
            .date-display {
                font-size: 1.1rem;
            }
            
            .content-wrapper {
                padding: 5rem 1.5rem 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .top-navbar .mobile-menu-btn {
                display: block;
            }

            .top-navbar {
                padding: 0.75rem 1rem;
            }

            .nav-brand h1 {
                font-size: 1.5rem;
            }

            .nav-actions {
                gap: 0.5rem;
            }
            
            .top-navbar .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary);
                box-shadow: var(--card-shadow);
                z-index: 999;
            }

            .top-navbar .nav-menu.active {
                display: flex;
            }

            .top-navbar .nav-menu li {
                width: 100%;
            }

            .top-navbar .nav-menu li a {
                width: 100%;
                padding: 1rem;
                text-align: center;
                display: block;
            }

            .nav-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.9rem;
            }

            .nav-btn i {
                margin-right: 0.25rem;
            }
            
            .date-time-container {
                padding: 1rem;
                margin-bottom: 1.5rem;
            }

            .clock-display {
                font-size: 1.75rem;
                padding: 0.5rem;
            }
            
            .date-display {
                font-size: 1rem;
                margin-bottom: 0;
            }

            .report-cards {
                gap: 1rem;
                padding: 0.5rem;
            }

            .report-card {
                padding: 1.5rem 1rem;
                min-height: 200px;
            }

            .icon-circle {
                width: 80px;
                height: 80px;
                margin-bottom: 1rem;
            }

            .report-card h2 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .report-card p {
                font-size: 0.9rem;
                margin-bottom: 0.75rem;
            }

            .report-card i {
                font-size: 2em !important;
            }

            .content-wrapper {
                padding: 5rem 1rem 1rem;
            }
            
            .nav-menu .nav-link {
                min-width: 100%;
                justify-content: flex-start;
                padding: 1rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .report-cards {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 0.75rem;
            }
            
            .report-card {
                padding: 1.25rem 0.75rem;
                min-height: 160px;
            }
            
            .icon-circle {
                width: 60px;
                height: 60px;
                margin-bottom: 0.75rem;
            }
            
            .report-card h2 {
                font-size: 1rem;
                margin-bottom: 0.35rem;
            }
            
            .report-card p {
                font-size: 0.8rem;
                margin-bottom: 0.5rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .report-card i {
                font-size: 1.5em !important;
            }
            
            .content-wrapper {
                padding: 5rem 0.5rem 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .nav-actions {
                flex-wrap: wrap;
            }
            
            .nav-btn span {
                display: none;
            }
            
            .nav-btn {
                padding: 0.5rem;
            }
            
            .nav-btn i {
                margin-right: 0;
                font-size: 1.2rem;
            }
            
            .top-navbar .nav-brand h1 {
                font-size: 1.2rem;
            }
            
            .date-time-container {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }
            
            .clock-display {
                font-size: 1.5rem;
            }
            
            .date-display {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 360px) {
            .report-cards {
                grid-template-columns: 1fr;
            }
            
            .report-card {
                min-height: 130px;
            }
            
            .icon-circle {
                width: 50px;
                height: 50px;
                margin-bottom: 0.5rem;
            }
        }

        /* Add touch-friendly interactions */
        @media (hover: none) {
            .report-card {
                transition: transform 0.2s ease;
            }
            
            .report-card:active {
                transform: scale(0.98);
                background-color: #f8f9fa;
            }
            
            .nav-btn:active {
                background-color: rgba(255, 255, 255, 0.2);
            }
        }
    </style>
</head>

<body>
    <nav class="top-navbar">
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-brand">
            <h1>Reports Dashboard</h1>
        </div>
        <ul class="nav-menu">
            <?php
            $currentPage = basename($_SERVER['PHP_SELF']);
            $navItems = [
                'pos_dashboard.php' => ['POS', 'fas fa-cash-register'],
                'setup.php' => ['Setup', 'fas fa-cog'],
                'entry.php' => ['Entry', 'fas fa-edit'],
                'reports.php' => ['Reports', 'fas fa-file-alt']
            ];

            foreach ($navItems as $page => $info) {
                $isActive = ($currentPage === $page) ? ' active' : '';
                echo "<li><a href='$page' class='nav-link$isActive'>
                        <i class='{$info[1]}'></i>
                        <span>{$info[0]}</span>
                      </a></li>";
            }
            ?>
        </ul>
        <div class="nav-actions">
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="../../../server/admin/admin_panel.php" class="nav-btn admin">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Panel</span>
                </a>
            <?php endif; ?>
            <a href="../../../server/auth/logout.php" class="nav-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="date-time-container">
            <div class="clock-display" id="clock">12:00:00</div>
            <div class="date-display" id="date">Monday, January 1, 2024</div>
        </div>

        <div class="report-cards">
            <?php 
            // Helper function to generate consistent color based on key
            function getRandomColor($key) {
                $colors = [
                    '#3b82f6', '#22c55e', '#ef4444', '#f59e0b', '#8b5cf6', 
                    '#ec4899', '#0891b2', '#64748b', '#84cc16', '#14b8a6', 
                    '#6366f1', '#d946ef', '#f43f5e', '#0ea5e9'
                ];
                
                // Get a consistent hash from the key string
                $hash = 0;
                for ($i = 0; $i < strlen($key); $i++) {
                    $hash = ord($key[$i]) + (($hash << 5) - $hash);
                }
                
                // Use the hash to pick a color and ensure positive index
                $index = (($hash % count($colors)) + count($colors)) % count($colors);
                return $colors[$index];
            }

            // Always show at least these important reports
            $always_show = ['daily_sale', 'stock_report', 'cashbook'];
            
            foreach ($report_items as $key => $item):
                if (in_array($key, $always_show) || showAllReports($pdo, $role_id, 'Reports', $key)): 
                    $color = isset($item['color']) ? $item['color'] : getRandomColor($key);
            ?>
                <a href="<?= $item['link'] ?>" style="text-decoration: none;">
                    <div class="report-card">
                        <div class="icon-circle" style="background-color: <?= $color ?>">
                            <i class="<?= $item['icon'] ?> fa-3x"></i>
                        </div>
                        <h2><?= $item['name'] ?></h2>
                        <p><?= $item['description'] ?></p>
                    </div>
                </a>
            <?php 
                endif;
            endforeach; 
            
            // If no cards were displayed, show a message
            if (empty($report_items)): 
            ?>
                <div style="text-align: center; width: 100%; padding: 2rem;">
                    <h2 style="color: #64748b;">No reports available</h2>
                    <p style="color: #94a3b8;">Please contact your administrator to set up reports.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../../assets/js/dashboard/reports.js"></script>
</body>

</html>