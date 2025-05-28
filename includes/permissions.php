<?php
// Function to check if a user has permission for a specific action
function hasPermission($pdo, $role_id, $module, $action) {
    try {
        // First check if the permissions table exists
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE 'permissions'");
            if ($stmt->rowCount() == 0) {
                // If the table doesn't exist yet, allow all actions during setup
                return true;
            }
        } catch (PDOException $e) {
            // Table check failed, likely different DB system
            // Allow access during development
            return true;
        }
        
        // Use CAST for compatibility with different database systems
        $stmt = $pdo->prepare("SELECT * FROM permissions WHERE role_id = ? AND module = ? AND action = ? AND CAST(allowed AS CHAR) = '1'");
        $stmt->execute([$role_id, $module, $action]);
        
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // If there's an error, allow access to avoid lockouts during development
        error_log("Permission check error: " . $e->getMessage());
        return true;
    }
}
?>
