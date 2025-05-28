<?php
// This is the view file - no database operations should be here
// Just render the data provided by the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Chart of Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/coa.css">
</head>
<body>
    <a href="../../../controllers/dashboard/dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    <h1>Setup Chart of Accounts</h1>

    <?php if (isset($_GET['error'])): ?>
    <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
    <div class="success-message">Operation completed successfully!</div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Add Account Head</h2>
        <form method="POST" action="../../../controllers/setup/coa/index.php">
            <label for="name">Account Head Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_account_head">Add Account Head</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Sub-Account</h2>
        <form method="POST" action="../../../controllers/setup/coa/index.php">
            <label for="account_head_id">Select Account Head:</label>
            <select id="account_head_id" name="account_head_id" required>
                <?php foreach ($account_heads as $head): ?>
                    <option value="<?= htmlspecialchars($head['id']) ?>"><?= htmlspecialchars($head['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="name">Sub-Account Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_sub_account">Add Sub-Account</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Account</h2>
        <form method="POST" action="../../../controllers/setup/coa/index.php">
            <label for="sub_account_id">Select Sub-Account:</label>
            <select id="sub_account_id" name="sub_account_id" required>
                <?php foreach ($sub_accounts as $sub_account): ?>
                    <option value="<?= htmlspecialchars($sub_account['id']) ?>"><?= htmlspecialchars($sub_account['sub_name']) ?> (<?= htmlspecialchars($sub_account['account_head_name']) ?>)</option>
                <?php endforeach; ?>
            </select>
            <label for="name">Account Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_account">Add Account</button>
        </form>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Account Heads</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchHeads" class="search-input" placeholder="Search account heads...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Heads')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Heads')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="headsList">
            <?php foreach ($account_heads as $head): ?>
                <li>
                    <?= htmlspecialchars($head['name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($head['id']) ?>', 'account_head', '<?= htmlspecialchars($head['name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($head['id']) ?>', 'account_head', '<?= htmlspecialchars($head['name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Sub-Accounts</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchSubs" class="search-input" placeholder="Search sub-accounts...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Subs')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Subs')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="subsList">
            <?php foreach ($sub_accounts as $sub_account): ?>
                <li>
                    <?= htmlspecialchars($sub_account['account_head_name']) ?> - <?= htmlspecialchars($sub_account['sub_name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($sub_account['id']) ?>', 'sub_account', '<?= htmlspecialchars($sub_account['sub_name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($sub_account['id']) ?>', 'sub_account', '<?= htmlspecialchars($sub_account['sub_name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="list-container">
        <div class="list-header">
            <h2 class="list-title">Existing Accounts</h2>
            <div class="search-container">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchAccounts" class="search-input" placeholder="Search accounts...">
                </div>
                <div class="search-buttons">
                    <button type="button" class="btn-search" onclick="performSearch('Accounts')">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button type="button" class="btn-cancel-search" onclick="cancelSearch('Accounts')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <ul id="accountsList">
            <?php foreach ($accounts as $account): ?>
                <li>
                    <?= htmlspecialchars($account['account_head_name']) ?> - <?= htmlspecialchars($account['sub_account_name']) ?> - <?= htmlspecialchars($account['name']) ?>
                    <div>
                        <button class="edit-button" onclick="openEditModal('<?= htmlspecialchars($account['id']) ?>', 'account', '<?= htmlspecialchars($account['name']) ?>')">Edit</button>
                        <button class="delete-button" onclick="showDeleteConfirmation('<?= htmlspecialchars($account['id']) ?>', 'account', '<?= htmlspecialchars($account['name']) ?>')">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <h2>Edit</h2>
            <form method="POST" action="../../../controllers/setup/coa/index.php">
                <input type="hidden" id="edit_id" name="edit_id">
                <input type="hidden" id="edit_type" name="edit_type">
                <label for="edit_name">Name:</label>
                <input type="text" id="edit_name" name="edit_name" required>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
            <h2>Delete Confirmation</h2>
            <p id="deleteConfirmationText"></p>
            <div class="modal-buttons">
                <button id="cancelDeleteBtn" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button id="confirmDeleteBtn" class="btn-confirm-delete" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/setup/coa.js"></script>
</body>
</html>