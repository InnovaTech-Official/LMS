<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Dashboard</title>
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
            <h1>Dashboard</h1>
        </div>
        <ul class="nav-menu">
            <?php
            foreach ($navItems as $page => $info):
                $isActive = ($currentPage === $page) ? ' active' : '';
                echo "<li><a href='$page' class='nav-link$isActive'>
                        <i class='{$info[1]}'></i>
                        <span>{$info[0]}</span>
                      </a></li>";
            endforeach; ?>
        </ul>
        <div class="nav-actions">
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="../admin/admin_panel.php" class="nav-btn admin">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Admin Panel</span>
                </a>
            <?php endif; ?>
            <a href="../auth/logout.php" class="nav-btn">
                <i class="fa-solid fa-sign-out"></i>
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
                $hasPermission = isset($pdo) && isset($role_id) ?
                    (function_exists('hasPermission') ? hasPermission($pdo, $role_id, 'POS', $key) : true) :
                    true;

                if ($hasPermission): ?>
                    <a href="<?= $item['link'] ?>" style="text-decoration: none;">
                        <div class="pos-card" <?= isset($item['primary']) && $item['primary'] ? 'class="primary"' : '' ?>>
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