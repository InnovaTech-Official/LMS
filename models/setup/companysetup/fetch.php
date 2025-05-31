<?php
// Model to fetch company settings data
require_once(__DIR__ . '/../../../includes/db_wrapper.php');

/**
 * Create or update the company_settings table structure
 * 
 * @param PDO $pdo Database connection
 * @return bool|string True on success, error message on failure
 */
function createOrUpdateTable($pdo) {
    $create_table_query = "CREATE TABLE IF NOT EXISTS company_settings (
        id SERIAL PRIMARY KEY,
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
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // Also check if we need a trigger for updated_at
    $check_trigger_query = "SELECT 1 FROM pg_trigger WHERE tgname = 'update_company_settings_timestamp'";
    
    try {
        $pdo->exec($create_table_query);
        
        $result = $pdo->query($check_trigger_query);
        if ($result->rowCount() === 0) {
            // Create trigger function if it doesn't exist
            $create_function = "
            CREATE OR REPLACE FUNCTION update_timestamp()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            $$ language 'plpgsql';";
            
            $pdo->exec($create_function);
            
            // Create trigger
            $create_trigger = "
            CREATE TRIGGER update_company_settings_timestamp
            BEFORE UPDATE ON company_settings
            FOR EACH ROW
            EXECUTE FUNCTION update_timestamp();";
            
            $pdo->exec($create_trigger);
        }

        // Check if phone_1 and phone_2 columns exist, if not add them
        $check_columns_query = "SELECT column_name FROM information_schema.columns 
                               WHERE table_name = 'company_settings' AND column_name = 'phone_1'";
        $result = $pdo->query($check_columns_query);
        if ($result->rowCount() === 0) {
            // Add phone_1 and phone_2 columns
            $alter_table_query = "ALTER TABLE company_settings 
                DROP COLUMN IF EXISTS phone,
                ADD COLUMN phone_1 VARCHAR(20),
                ADD COLUMN phone_2 VARCHAR(20)";
            $pdo->exec($alter_table_query);
        }
        return true;
    } catch (PDOException $e) {
        return "Error creating/updating table: " . $e->getMessage();
    }
}

/**
 * Fetch company settings data
 * 
 * @param PDO $pdo Database connection
 * @return array|null Company data or null if not found
 */
function getCompanyData($pdo) {
    try {
        $check_query = "SELECT * FROM company_settings LIMIT 1";
        $stmt = $pdo->query($check_query);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    } catch (PDOException $e) {
        return null;
    }
}