<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <nav class="top-navbar">
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-brand">
            <h1>POS Dashboard</h1>
        </div>
        <ul class="nav-menu">
            <?php foreach ($navItems as $page => $info): 
                $isActive = ($currentPage === $page) ? ' active' : '';
                echo "<li><a href='$page' class='nav-link$isActive'>
                        <i class='{$info[1]}'></i>
                        <span>{$info[0]}</span>
                      </a></li>";
            endforeach; ?>
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

        <div class="pos-cards">
            <?php foreach ($pos_items as $key => $item):
                if ($key == 'pos_sale' || $key == 'daily_sales' || $key == 'customer_lookup' || $key == 'products' || $key == 'receive_payment' || $key == 'cashbook' || $key == 'ledger' || $key == 'stock' || hasPermission($pdo, $role_id, 'POS', $key)): ?>
                    <a href="<?= $item['link'] ?>" style="text-decoration: none;">
                        <div class="pos-card" <?= isset($item['primary']) && $item['primary'] ? 'style="border: 2px solid var(--success);"' : '' ?>>
                            <div class="icon-circle" style="background-color: <?= $item['color'] ?>">
                                <i class="<?= $item['icon'] ?>"></i>
                            </div>
                            <h2><?= $item['name'] ?></h2>
                            <p><?= $item['description'] ?></p>
                        </div>
                    </a>
                <?php endif;
            endforeach; ?>
        </div>
    </div>

    <script src="../../assets/js/dashboard/dashboard.js"></script>
</body>

</html>