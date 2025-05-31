<?php
// Model to create new company settings
require_once(__DIR__ . '/../../../includes/db_wrapper.php');


/**
 * Insert new company settings
 * 
 * @param PDO $pdo Database connection
 * @param array $data Form data
 * @param array $files Uploaded files
 * @return array Result with success status and message
 */
function createCompanySettings($pdo, $data, $files) {
    try {
        // Handle logo upload
        $logo_path = null;
        if (isset($files['company_logo']) && $files['company_logo']['error'] === UPLOAD_ERR_OK) {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../../../views/setup/companysetup/uploads/company_logo/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file = $files['company_logo'];
            
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

        $company_name = $data['company_name'] ?? '';
        $address_line1 = $data['address_line1'] ?? '';
        $address_line2 = $data['address_line2'] ?? '';
        $city = $data['city'] ?? '';
        $state = $data['state'] ?? '';
        $postal_code = $data['postal_code'] ?? '';
        $country = $data['country'] ?? '';
        $phone_1 = $data['phone_1'] ?? '';
        $phone_2 = $data['phone_2'] ?? '';
        $email = $data['email'] ?? '';
        $website = $data['website'] ?? '';
        $tax_id = $data['tax_id'] ?? '';
        $registration_number = $data['registration_number'] ?? '';

        // Validate required fields
        $required_fields = ['company_name', 'address_line1', 'city', 'state', 'postal_code', 'country', 'phone_1'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $missing_fields[] = ucwords(str_replace('_', ' ', $field));
            }
        }
        
        if (!empty($missing_fields)) {
            return [
                'success' => false,
                'message' => "Please fill in the following required fields: " . implode(", ", $missing_fields)
            ];
        }

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

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => "Company settings created successfully!"
            ];
        } else {
            return [
                'success' => false,
                'message' => "Error saving settings: " . $pdo->errorInfo()[2]
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ];
    }
}