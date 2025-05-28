<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
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
            <?php 
            // Reorder to show primary item first
            $reordered = array();
            
            // First add highlighted items
            foreach ($report_items as $key => $item) {
                if (isset($item['highlight']) && $item['highlight'] && 
                    (isset($pdo) && isset($role_id) ? 
                    (function_exists('hasPermission') ? hasPermission($pdo, $role_id, 'Reports', $key) : true) : 
                    true)) {
                    $reordered[$key] = $item;
                }
            }
            
            // Then add other items
            foreach ($report_items as $key => $item) {
                if (!isset($item['highlight']) || !$item['highlight']) {
                    if (isset($pdo) && isset($role_id) ? 
                        (function_exists('hasPermission') ? hasPermission($pdo, $role_id, 'Reports', $key) : true) : 
                        true) {
                        $reordered[$key] = $item;
                    }
                }
            }
            
            // Now display cards
            foreach ($reordered as $key => $item):
                $highlight_class = isset($item['highlight']) && $item['highlight'] ? 'primary' : '';
            ?>
                <a href="<?= $item['link'] ?>" style="text-decoration: none;">
                    <div class="pos-card <?= $highlight_class ?>">
                        <div class="icon-circle" style="background-color: <?= $item['color'] ?>">
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
        // Simple clock and date display
        function updateClock() {
            const now = new Date();
            
            // Format time as HH:MM:SS AM/PM (12-hour format)
            let hours = now.getHours();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
            
            // Format date as Day, Month DD, YYYY
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date').textContent = now.toLocaleDateString('en-US', options);
        }
        
        // Update immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
        
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    </script>
</body>
</html>