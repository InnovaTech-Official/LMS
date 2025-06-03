<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

function getId($id, $pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM branches WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return ['success' => true, 'data' =>  $result];
        } else {
            return ['success' => false, 'data' => []];
        }
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

function updateBranch($pdo, $data)
{
    $id = $data['id']; // Make sure this is passed in the request

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

    $borrowerUniqueNumber = !empty($data['borrowerUniqueNumber']) ? $data['borrowerUniqueNumber'] : '0000';
    $loanUniqueNumber = !empty($data['loanUniqueNumber']) ? $data['loanUniqueNumber'] : '0000';

    $updateQuery = "
        UPDATE branches SET 
            branch_name = :branchName,
            branch_open_date = :branchOpenDate,
            country = :country,
            currency = :currency,
            date_format = :dateFormat,
            currency_in_words = :currencyInWords,
            branch_address = :branchAddress,
            branch_city = :branchCity,
            branch_province = :branchProvince,
            branch_mobile = :branchMobile,
            borrower_unique_number = :borrowerUniqueNumber,
            loan_unique_number = :loanUniqueNumber
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($updateQuery);

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
        ':loanUniqueNumber' => $loanUniqueNumber,
        ':id' => $id
    ]);

    return ['success' => true, 'message' => 'Branch updated successfully.'];
}
