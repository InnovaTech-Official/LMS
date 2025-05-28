<?php
session_start();
require_once '../../../controllers/setup/banksetup/index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Setup</title>
    <link rel="stylesheet" href="../../../assets/css/bankSetup.css">
    <?php echo $js_code; ?>
</head>
<body>
    <h1>Bank Setup</h1>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h2>Add Bank Account</h2>
            <form method="POST" action="../../../controllers/setup/banksetup/post.php">
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" name="bank_name" required>
                <label for="branch_name">Branch Name:</label>
                <input type="text" id="branch_name" name="branch_name">
                <label for="branch_code">Branch Code:</label>
                <input type="text" id="branch_code" name="branch_code">
                <label for="account_number">Account Number:</label>
                <input type="text" id="account_number" name="account_number" required>
                <label for="account_title">Account Title:</label>
                <input type="text" id="account_title" name="account_title" required>
                <label for="iban">IBAN:</label>
                <input type="text" id="iban" name="iban">
                <label for="swift_code">Swift Code:</label>
                <input type="text" id="swift_code" name="swift_code">
                <label for="bank_address">Bank Address:</label>
                <textarea id="bank_address" name="bank_address"></textarea>
                <label for="contact_person">Contact Person:</label>
                <input type="text" id="contact_person" name="contact_person">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <button type="submit">Add Bank Account</button>
            </form>
        </div>

        <div class="accounts-list">
            <h2>Existing Bank Accounts</h2>
            <table>
                <tr>
                    <th>Bank Name</th>
                    <th>Branch Name</th>
                    <th>Branch Code</th>
                    <th>Account Number</th>
                    <th>Account Title</th>
                    <th>IBAN</th>
                    <th>Swift Code</th>
                    <th>Bank Address</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php if ($bank_data && count($bank_data) > 0): ?>
                    <?php foreach ($bank_data as $data): ?>
                        <tr data-id="<?= $data['id'] ?>">
                            <td><?= htmlspecialchars($data['bank_name']) ?></td>
                            <td><?= htmlspecialchars($data['branch_name']) ?></td>
                            <td><?= htmlspecialchars($data['branch_code']) ?></td>
                            <td><?= htmlspecialchars($data['account_number']) ?></td>
                            <td><?= htmlspecialchars($data['account_title']) ?></td>
                            <td><?= htmlspecialchars($data['iban']) ?></td>
                            <td><?= htmlspecialchars($data['swift_code']) ?></td>
                            <td><?= htmlspecialchars($data['bank_address']) ?></td>
                            <td><?= htmlspecialchars($data['contact_person']) ?></td>
                            <td><?= htmlspecialchars($data['contact_number']) ?></td>
                            <td><?= htmlspecialchars($data['email']) ?></td>
                            <td>
                                <div class="button-container">
                                    <button class="edit-button" onclick="openEditModal('<?= $data['id'] ?>')">Edit</button>
                                    <form method="POST" action="../../../controllers/setup/banksetup/delete.php" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?= $data['id'] ?>">
                                        <input type="hidden" name="delete" value="1">
                                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this bank account?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">No bank accounts found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <a href="../../../controllers/dashboard/dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Bank Account</h2>
            <form method="POST" action="../../../controllers/setup/banksetup/edit.php">
                <input type="hidden" id="edit_bank_account_id" name="id">
                <input type="hidden" name="edit" value="1">
                <div class="form-row">
                    <label for="edit_bank_name">Bank Name:</label>
                    <input type="text" id="edit_bank_name" name="bank_name" required>
                </div>
                <div class="form-row">
                    <label for="edit_branch_name">Branch Name:</label>
                    <input type="text" id="edit_branch_name" name="branch_name">
                </div>
                <div class="form-row">
                    <label for="edit_branch_code">Branch Code:</label>
                    <input type="text" id="edit_branch_code" name="branch_code">
                </div>
                <div class="form-row">
                    <label for="edit_account_number">Account Number:</label>
                    <input type="text" id="edit_account_number" name="account_number" required>
                </div>
                <div class="form-row">
                    <label for="edit_account_title">Account Title:</label>
                    <input type="text" id="edit_account_title" name="account_title" required>
                </div>
                <div class="form-row">
                    <label for="edit_iban">IBAN:</label>
                    <input type="text" id="edit_iban" name="iban">
                </div>
                <div class="form-row">
                    <label for="edit_swift_code">Swift Code:</label>
                    <input type="text" id="edit_swift_code" name="swift_code">
                </div>
                <div class="form-row">
                    <label for="edit_bank_address">Bank Address:</label>
                    <textarea id="edit_bank_address" name="bank_address"></textarea>
                </div>
                <div class="form-row">
                    <label for="edit_contact_person">Contact Person:</label>
                    <input type="text" id="edit_contact_person" name="contact_person">
                </div>
                <div class="form-row">
                    <label for="edit_contact_number">Contact Number:</label>
                    <input type="text" id="edit_contact_number" name="contact_number">
                </div>
                <div class="form-row">
                    <label for="edit_email">Email:</label>
                    <input type="email" id="edit_email" name="email">
                </div>
                <button type="submit" class="edit-button">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="../../../assets/js/setup/bankSetup.js"></script>
</body>
</html>