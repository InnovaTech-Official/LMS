<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../../assets/css/setup.css">
</head>

<body>
    <nav class="top-navbar">
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-brand">
            <h1>Setup Dashboard</h1>
        </div>
        <ul class="nav-menu">
            <?php
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

        <div class="setup-cards">
            <?php foreach ($setup_items as $key => $item):
                if (hasPermission($pdo, $role_id, 'Setup', $key)): ?>
                    <a href="<?= $item['link'] ?>" style="text-decoration: none;">
                        <div class="setup-card">
                            <div class="icon-circle" style="background-color: <?= isset($item['color']) ? $item['color'] : getRandomColor($key) ?>">
                                <i class="<?= ensureIconSize($item['icon']) ?>"></i>
                            </div>
                            <h2><?= $item['name'] ?></h2>
                            <p><?= $item['description'] ?></p>
                        </div>
                    </a>
                <?php endif;
            endforeach; ?>
        </div>
    </div>

    <script src="../../assets/js/dashboard/setup.js"></script>
</body>
</html>