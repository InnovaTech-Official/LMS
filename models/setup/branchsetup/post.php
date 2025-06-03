<?php
include_once(__DIR__ . "../../../../includes/db_wrapper.php");
include_once(__DIR__ . "../../../../includes/local_config.php");

function insertBranch($pdo, $data) {
    $branchName = $data['branchName'];
    $branchOpenDate = $data['branchOpenDate'];
    $country = $data['country'];
    $currency = $data['currency'];
    $dateFormat = $data['dateFormat'];
    $currencyInWords = $data['currencyInWords'];
    $branchAddress = isset($data['branchAddress']) ? $data['branchAddress'] : '';
    $branchCity = isset($data['branchCity']) ? $data['branchCity'] : '';
    $branchProvince = isset($data['branchProvince']) ? $data['branchProvince'] : '';
    $branchMobile = isset($data['branchMobile']) ? $data['branchMobile'] : '';
    $borrowerUniqueNumber = isset($data['borrowerUniqueNumber']) ? $data['borrowerUniqueNumber'] : '';
    $borrowerUniqueNumber = !empty($borrowerUniqueNumber) ? $borrowerUniqueNumber : '0000';
    $loanUniqueNumber = isset($data['loanUniqueNumber']) ? $data['loanUniqueNumber'] : '';
    $loanUniqueNumber = !empty($loanUniqueNumber) ? $loanUniqueNumber : '0000';


    // Create the branches table if it doesn't exist
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS branches (
            id SERIAL PRIMARY KEY,
            branch_name VARCHAR(255) NOT NULL,
            branch_open_date DATE NOT NULL,
            country VARCHAR(100) NOT NULL,
            currency VARCHAR(10) NOT NULL,
            date_format VARCHAR(20) NOT NULL,
            currency_in_words VARCHAR(255) NOT NULL,
            branch_address VARCHAR(255) NOT NULL,
            branch_city VARCHAR(100) NOT NULL,
            branch_province VARCHAR(100) NOT NULL,
            branch_mobile VARCHAR(20) NOT NULL,
            borrower_unique_number VARCHAR(20) NOT NULL DEFAULT '0000',
            loan_unique_number VARCHAR(20) NOT NULL DEFAULT '0000',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($createTableQuery);

    // Insert the new branch data
    $insertQuery = "
        INSERT INTO branches (branch_name, branch_open_date, country, currency, date_format, currency_in_words, branch_address, branch_city, branch_province, branch_mobile, borrower_unique_number, loan_unique_number)
        VALUES (:branchName, :branchOpenDate, :country, :currency, :dateFormat, :currencyInWords, :branchAddress, :branchCity, :branchProvince, :branchMobile, :borrowerUniqueNumber, :loanUniqueNumber)
    ";
    
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        ':branchName' => $branchName,
        ':branchOpenDate' => date('Y-m-d', strtotime($branchOpenDate)),
        ':country' => $country,
        ':currency' => $currency,
        ':dateFormat' => $dateFormat,
        ':currencyInWords' => $currencyInWords,
        ':branchAddress' => $branchAddress,
        ':branchCity' => $branchCity,
        ':branchProvince' => $branchProvince,
        ':branchMobile' => $branchMobile,
        ':borrowerUniqueNumber' => $borrowerUniqueNumber,   
        ':loanUniqueNumber' => $loanUniqueNumber
    ]);

    return ['success' => true, 'message' => 'Branch added successfully.'];
}





?>