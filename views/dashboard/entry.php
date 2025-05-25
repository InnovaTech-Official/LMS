<?php
session_start();
include '../../../server/config/db_wrapper.php';
include '../../../server/config/permissions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../server/auth/login.php');
    exit;
}

$role_id = $_SESSION['role_id'];

// Define entry items
$entry_items = [
    'pos_bill' => [
        'name' => 'POS Sales Bill',
        'description' => 'Generate and manage point of sale transactions',
        'icon' => 'fas fa-cash-register fa-3x',
        'link' => '../entry/pos/pos_bill.php',
        'highlight' => true  // Add highlight flag for POS
    ],
    'sale_return' => [
        'name' => 'Sale Return Invoice',
        'description' => 'Process and manage sales returns and refunds',
        'icon' => 'fas fa-undo-alt fa-3x',
        'link' => '../entry/sale_return/'
    ],
    'purchase_invoice' => [
        'name' => 'Purchase Invoice',
        'description' => 'Record and track purchase transactions',
        'icon' => 'fas fa-shopping-cart fa-3x',
        'link' => '../entry/purchase_invoice/'
    ],
    'purchase_return' => [
        'name' => 'Purchase Return Invoice',
        'description' => 'Manage returns to vendors and suppliers',
        'icon' => 'fas fa-truck-loading fa-3x',
        'link' => '../entry/purchase_return/'
    ],
    'receive_voucher' => [
        'name' => 'Receive Voucher',
        'description' => 'Process incoming payments and receipts',
        'icon' => 'fas fa-hand-holding-usd fa-3x',
        'link' => '../accounts/vouchers/receive_voucher/receive_voucher.php'
    ],
    'receive_voucher2' => [
        'name' => 'Receive Voucher 2',
        'description' => 'Alternative receipt processing system',
        'icon' => 'fas fa-money-bill-wave fa-3x',
        'link' => '../accounts/vouchers/receive_voucher2/'
    ],
    'payment_voucher' => [
        'name' => 'Payment Voucher',
        'description' => 'Record and track outgoing payments',
        'icon' => 'fas fa-money-check-alt fa-3x',
        'link' => '../accounts/vouchers/payment_voucher/payment_voucher.php'
    ],
    'journal_voucher' => [
        'name' => 'Journal Voucher',
        'description' => 'Manage journal entries and adjustments',
        'icon' => 'fas fa-book fa-3x',
        'link' => '../accounts/vouchers/journal_voucher/journal_voucher.php'
    ],
    'expense_voucher' => [
        'name' => 'Expense Voucher',
        'description' => 'Track and record business expenses',
        'icon' => 'fas fa-receipt fa-3x',
        'link' => '../accounts/vouchers/expense_voucher/expns.php'
    ],
    'employee_entry' => [
        'name' => 'Employee Entry',
        'description' => 'Manage employee records and information',
        'icon' => 'fas fa-user-tie fa-3x',
        'link' => '../entry/employees/manage_employees.php'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entry</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        .entry-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .entry-card {
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

        .entry-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .entry-card i {
            margin-bottom: 1.5rem;
        }

        .entry-card h2 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .entry-card p {
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

        .entry-card:hover .icon-circle {
            transform: scale(1.1);
        }
        
        /* POS highlight styles */
        .entry-card.highlight .icon-circle {
            border: 3px solid var(--success);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.4);
        }
        
        .entry-card.highlight h2 {
            color: var(--success);
        }

        @media (max-width: 1400px) {
            .entry-cards {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .entry-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .entry-cards {
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

            .entry-cards {
                gap: 1rem;
                padding: 0.5rem;
            }

            .entry-card {
                padding: 1.5rem 1rem;
                min-height: 200px;
            }

            .icon-circle {
                width: 80px;
                height: 80px;
                margin-bottom: 1rem;
            }

            .entry-card h2 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .entry-card p {
                font-size: 0.9rem;
                margin-bottom: 0.75rem;
            }

            .entry-card i {
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
            .entry-cards {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 0.75rem;
            }
            
            .entry-card {
                padding: 1.25rem 0.75rem;
                min-height: 160px;
            }
            
            .icon-circle {
                width: 60px;
                height: 60px;
                margin-bottom: 0.75rem;
            }
            
            .entry-card h2 {
                font-size: 1rem;
                margin-bottom: 0.35rem;
            }
            
            .entry-card p {
                font-size: 0.8rem;
                margin-bottom: 0.5rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .entry-card i {
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
            .entry-cards {
                grid-template-columns: 1fr;
            }
            
            .entry-card {
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
            .entry-card {
                transition: transform 0.2s ease;
            }
            
            .entry-card:active {
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
            <h1>Entry Dashboard</h1>
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

        <div class="entry-cards">
            <?php 
            // Reorder to show POS first
            $reordered = array();
            if (isset($entry_items['pos_bill']) && hasPermission($pdo, $role_id, 'Entry', 'pos_bill')) {
                $reordered['pos_bill'] = $entry_items['pos_bill'];
            }
            
            foreach ($entry_items as $key => $item):
                if ($key !== 'pos_bill' && hasPermission($pdo, $role_id, 'Entry', $key)) {
                    $reordered[$key] = $item;
                }
            endforeach;
            
            // Function to generate consistent color based on key
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
                            
                            $index = (abs($hash) & PHP_INT_MAX) % count($colors);
                            return $colors[$index];
                        }
            
                        // Now display cards
                        foreach ($reordered as $key => $item):
                            $highlight_class = isset($item['highlight']) && $item['highlight'] ? 'highlight' : '';
                            $color = isset($item['color']) ? $item['color'] : getRandomColor($key);
                        ?>
                <a href="<?= $item['link'] ?>" style="text-decoration: none;" target="<?= isset($item['target']) ? $item['target'] : '_blank' ?>">
                    <div class="entry-card <?= $highlight_class ?>">
                        <div class="icon-circle" style="background-color: <?= $color ?>">
                            <i class="<?= $item['icon'] ?>"></i>
                        </div>
                        <h2><?= $item['name'] ?></h2>
                        <p><?= $item['description'] ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Helper function to generate consistent color based on key
        function getRandomColor(key) {
            const colors = [
                '#3b82f6', '#22c55e', '#ef4444', '#f59e0b', '#8b5cf6', 
                '#ec4899', '#0891b2', '#64748b', '#84cc16', '#14b8a6', 
                '#6366f1', '#d946ef', '#f43f5e', '#0ea5e9'
            ];
            
            // Get a consistent hash from the key string
            let hash = 0;
            for (let i = 0; i < key.length; i++) {
                hash = key.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            // Use the hash to pick a color
            const index = Math.abs(hash) % colors.length;
            return colors[index];
        }
        
        // Display live clock and date
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            document.getElementById('clock').textContent = now.toLocaleTimeString(undefined, timeOptions);
            document.getElementById('date').textContent = now.toLocaleDateString(undefined, dateOptions);
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Make entire card clickable
        document.querySelectorAll('.entry-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // Find the parent <a> tag and navigate to its href
                const link = card.closest('a');
                if (link && link.href) {
                    window.location.href = link.href;
                }
            });
        });

        // Add touch support for mobile devices
        document.querySelectorAll('.entry-card').forEach(card => {
            card.addEventListener('touchend', (e) => {
                e.preventDefault();
                const link = card.closest('a');
                if (link && link.href) {
                    window.location.href = link.href;
                }
            });
        });
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');

            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    navMenu.classList.toggle('active');
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        navMenu.classList.remove('active');
                    }
                });
            }

            // Close menu on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && navMenu) {
                    navMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>