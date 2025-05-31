<?php
// This file is included by the controller
// Direct access is not allowed
if (!defined('BASEPATH')) {
    define('BASEPATH', true);
}

// If someone tries to access this file directly, redirect to the controller
if (!isset($company_data) && !isset($success_message) && !isset($error_message)) {
    header('Location: ../../../controllers/setup/companysetup/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/companySetup.css">
</head>
<body>
    <div class="container mt-5">
        <div class="button-container">
            <a href="../../dashboard/dashboard.php" class="action-button back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="setup-form">
            <h2 class="form-title">Company Setup</h2>
            
            <?php if (isset($success_message) && $success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message) && $error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" action="../../../controllers/setup/companysetup/index.php">
                <div class="form-group">
                    <label for="company_logo">Company Logo</label>
                    <div class="logo-preview" id="logoPreview">
                        <?php if (isset($company_data) && $company_data && $company_data['company_logo']): ?>
                            <img src="<?php echo htmlspecialchars($company_data['company_logo']); ?>" alt="Company Logo" style="max-width: 200px;">
                        <?php else: ?>
                            <div class="text-muted">No logo uploaded</div>
                        <?php endif; ?>
                    </div>
                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                    <small class="form-text text-muted">Recommended size: 200x200px. Supported formats: JPG, PNG, GIF</small>
                </div>

                <div class="form-group">
                    <label for="company_name">Company/Firm Name*</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" 
                        value="<?= htmlspecialchars(isset($company_data) ? ($company_data['company_name'] ?? '') : '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="address_line1">Address Line 1*</label>
                    <input type="text" class="form-control" id="address_line1" name="address_line1" 
                        value="<?= htmlspecialchars(isset($company_data) ? ($company_data['address_line1'] ?? '') : '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" class="form-control" id="address_line2" name="address_line2" 
                        value="<?= htmlspecialchars(isset($company_data) ? ($company_data['address_line2'] ?? '') : '') ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">City*</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['city'] ?? '') : '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="state">State/Province*</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['state'] ?? '') : '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postal_code">Postal Code*</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['postal_code'] ?? '') : '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country*</label>
                            <input type="text" class="form-control" id="country" name="country" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['country'] ?? '') : '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_1">Primary Phone*</label>
                            <input type="tel" class="form-control" id="phone_1" name="phone_1" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['phone_1'] ?? '') : '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_2">Secondary Phone</label>
                            <input type="tel" class="form-control" id="phone_2" name="phone_2" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['phone_2'] ?? '') : '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['email'] ?? '') : '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" class="form-control" id="website" name="website" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['website'] ?? '') : '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tax_id">Tax ID</label>
                            <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['tax_id'] ?? '') : '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_number">Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" 
                                value="<?= htmlspecialchars(isset($company_data) ? ($company_data['registration_number'] ?? '') : '') ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit">
                    <?= isset($company_data) && $company_data ? 'Update Company Settings' : 'Save Company Settings' ?>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Logo preview
        document.getElementById('company_logo').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').innerHTML = 
                        `<img src="${e.target.result}" alt="Logo Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
