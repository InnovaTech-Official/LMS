<?php
session_start();
require_once('../../../controllers/config/db_wrapper.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../server/auth/login.php');
    exit;
}

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access denied. You are not authorized to view this page.";
    exit;
}

$success_message = '';
$error_message = '';

// Create company_settings table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS company_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(100) NOT NULL,
    company_logo VARCHAR(255),
    address_line1 VARCHAR(100),
    address_line2 VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(50),
    postal_code VARCHAR(20),
    country VARCHAR(50),
    phone_1 VARCHAR(20),
    phone_2 VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    tax_id VARCHAR(50),
    registration_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($create_table_query);

    // Check if phone_1 and phone_2 columns exist, if not add them
    $check_columns_query = "SHOW COLUMNS FROM company_settings LIKE 'phone_1'";
    $result = $pdo->query($check_columns_query);
    if ($result->rowCount() === 0) {
        // Add phone_1 and phone_2 columns
        $alter_table_query = "ALTER TABLE company_settings 
            DROP COLUMN IF EXISTS phone,
            ADD COLUMN phone_1 VARCHAR(20) AFTER country,
            ADD COLUMN phone_2 VARCHAR(20) AFTER phone_1";
        $pdo->exec($alter_table_query);
    }
} catch (PDOException $e) {
    $error_message = "Error creating/updating table: " . $e->getMessage();
}

// Check if company settings already exist
$company_data = null;
try {
    $check_query = "SELECT * FROM company_settings LIMIT 1";
    $stmt = $pdo->query($check_query);
    if ($stmt) {
        $company_data = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error_message = "Error fetching company data: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle logo upload
        $logo_path = null;
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            // Create uploads directory if it doesn't exist
            $upload_dir = 'uploads/company_logo/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file = $_FILES['company_logo'];
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'company_logo_' . uniqid() . '.' . $extension;
            $logo_path = $upload_dir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $logo_path)) {
                throw new Exception('Failed to upload logo');
            }
        }

        $company_name = $_POST['company_name'] ?? '';
        $address_line1 = $_POST['address_line1'] ?? '';
        $address_line2 = $_POST['address_line2'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $postal_code = $_POST['postal_code'] ?? '';
        $country = $_POST['country'] ?? '';
        $phone_1 = $_POST['phone_1'] ?? '';
        $phone_2 = $_POST['phone_2'] ?? '';
        $email = $_POST['email'] ?? '';
        $website = $_POST['website'] ?? '';
        $tax_id = $_POST['tax_id'] ?? '';
        $registration_number = $_POST['registration_number'] ?? '';

        // Validate required fields
        $required_fields = ['company_name', 'address_line1', 'city', 'state', 'postal_code', 'country', 'phone_1'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = ucwords(str_replace('_', ' ', $field));
            }
        }
        
        if (!empty($missing_fields)) {
            $error_message = "Please fill in the following required fields: " . implode(", ", $missing_fields);
        } else {
            if ($company_data) {
                // Update existing settings
                $query = "UPDATE company_settings SET 
                    company_name = :company_name,
                    " . ($logo_path ? "company_logo = :company_logo," : "") . "
                    address_line1 = :address_line1,
                    address_line2 = :address_line2,
                    city = :city,
                    state = :state,
                    postal_code = :postal_code,
                    country = :country,
                    phone_1 = :phone_1,
                    phone_2 = :phone_2,
                    email = :email,
                    website = :website,
                    tax_id = :tax_id,
                    registration_number = :registration_number 
                    WHERE id = :id";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':company_name', $company_name);
                if ($logo_path) {
                    $stmt->bindParam(':company_logo', $logo_path);
                }
                $stmt->bindParam(':address_line1', $address_line1);
                $stmt->bindParam(':address_line2', $address_line2);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':state', $state);
                $stmt->bindParam(':postal_code', $postal_code);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':phone_1', $phone_1);
                $stmt->bindParam(':phone_2', $phone_2);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':website', $website);
                $stmt->bindParam(':tax_id', $tax_id);
                $stmt->bindParam(':registration_number', $registration_number);
                $stmt->bindParam(':id', $company_data['id']);
            } else {
                // Insert new settings
                $query = "INSERT INTO company_settings 
                    (company_name, " . ($logo_path ? "company_logo," : "") . "
                    address_line1, address_line2, city, state, postal_code, country, 
                    phone_1, phone_2, email, website, tax_id, registration_number) 
                    VALUES (:company_name, " . ($logo_path ? ":company_logo," : "") . "
                    :address_line1, :address_line2, :city, :state, :postal_code, :country, 
                    :phone_1, :phone_2, :email, :website, :tax_id, :registration_number)";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':company_name', $company_name);
                if ($logo_path) {
                    $stmt->bindParam(':company_logo', $logo_path);
                }
                $stmt->bindParam(':address_line1', $address_line1);
                $stmt->bindParam(':address_line2', $address_line2);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':state', $state);
                $stmt->bindParam(':postal_code', $postal_code);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':phone_1', $phone_1);
                $stmt->bindParam(':phone_2', $phone_2);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':website', $website);
                $stmt->bindParam(':tax_id', $tax_id);
                $stmt->bindParam(':registration_number', $registration_number);
            }

            if ($stmt->execute()) {
                $success_message = "Company settings saved successfully!";
                // Refresh company data
                $stmt = $pdo->query("SELECT * FROM company_settings LIMIT 1");
                $company_data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Error saving settings: " . $pdo->errorInfo()[2];
            }
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
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
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .setup-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
        }
        .button-container {
            margin-bottom: 30px;
            text-align: right;
        }
        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-button.back {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: white !important;
            text-decoration: none;
        }
        .form-title {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #0d6efd, #0099ff);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #0099ff, #0d6efd);
            transform: translateY(-1px);
        }
        .alert {
            margin-bottom: 20px;
        }
        .logo-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .logo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="button-container">
            <a href="../../dashboard/pos_dashboard.php" class="action-button back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="setup-form">
            <h2 class="form-title">Company Setup</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="company_logo">Company Logo</label>
                    <div class="logo-preview" id="logoPreview">
                        <?php if ($company_data && $company_data['company_logo']): ?>
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
                        value="<?= htmlspecialchars($company_data['company_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="address_line1">Address Line 1*</label>
                    <input type="text" class="form-control" id="address_line1" name="address_line1" 
                        value="<?= htmlspecialchars($company_data['address_line1'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" class="form-control" id="address_line2" name="address_line2" 
                        value="<?= htmlspecialchars($company_data['address_line2'] ?? '') ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">City*</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                value="<?= htmlspecialchars($company_data['city'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="state">State/Province*</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                value="<?= htmlspecialchars($company_data['state'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postal_code">Postal Code*</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                value="<?= htmlspecialchars($company_data['postal_code'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country*</label>
                            <input type="text" class="form-control" id="country" name="country" 
                                value="<?= htmlspecialchars($company_data['country'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_1">Primary Phone*</label>
                            <input type="tel" class="form-control" id="phone_1" name="phone_1" 
                                value="<?= htmlspecialchars($company_data['phone_1'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_2">Secondary Phone</label>
                            <input type="tel" class="form-control" id="phone_2" name="phone_2" 
                                value="<?= htmlspecialchars($company_data['phone_2'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= htmlspecialchars($company_data['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" class="form-control" id="website" name="website" 
                                value="<?= htmlspecialchars($company_data['website'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tax_id">Tax ID</label>
                            <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                value="<?= htmlspecialchars($company_data['tax_id'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_number">Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" 
                                value="<?= htmlspecialchars($company_data['registration_number'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit">
                    <?= $company_data ? 'Update Company Settings' : 'Save Company Settings' ?>
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
