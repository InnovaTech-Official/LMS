<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

/**
 * Insert new loan settings
 * 
 * @param PDO $pdo Database connection
 * @param array $data Form data
 * @return array Result with success status and message
 */

function insertLoan($pdo, $data)
{

    $createLoanTabeQuery = "
        CREATE TABLE IF NOT EXISTS loan_products(
            id SERIAL PRIMARY KEY,
            loan_product_name VARCHAR(255),
            access_to_branch TEXT,
            enable_parameters BOOLEAN,
            min_principal_amount NUMERIC,
            default_principal_amount NUMERIC,
            max_principal_amount NUMERIC,
            interest_method VARCHAR(50),
            interest_type VARCHAR(50),
            loan_interest_period VARCHAR(50),
            loan_interest NUMERIC,
            loan_duration_period VARCHAR(50),
            repayment_frequency TEXT,
            num_repayments INT,
            repayment_order_list TEXT,
            fee_input TEXT,
            loan_duration Text,
            loan_status VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $pdo->exec($createLoanTabeQuery);

    $loanProductName = $data['loanProductName'];
    $accessToBranch = $data['accessToBranch'];
    $enableParameters = isset($data['enableParameters']) ? $data['enableParameters'] : 0;
    $minPrincipalAmount = !empty($data['minPrincipalAmount']) ? $data['minPrincipalAmount'] : 0;
    $defaultPrincipalAmount = !empty($data['defaultPrincipalAmount']) ? $data['defaultPrincipalAmount'] : 0;
    $maxPrincipalAmount = !empty($data['maxPrincipalAmount']) ? $data['maxPrincipalAmount'] : 0;
    $interestMethod = $data['interestMethod'];
    $interestType = $data['interestType'];
    $loanInterestPeriod = $data['loanInterestPeriod'];
    $loanInterest = !empty($data['loanInterest']) ? $data['loanInterest'] : 0;
    $loanDurationPeriod = $data['loanDurationPeriod'];
    $repaymentFrequency = isset($data['repaymentFrequency']) && is_array($data['repaymentFrequency']) ? implode(',', $data['repaymentFrequency']) : '';
    $numRepayments = !empty($data['numRepayments']) ? $data['numRepayments'] : 0;
    $repaymentOrderList = isset($data['repaymentOrderList']) ? $data['repaymentOrderList'] : '';
    $feeinput = isset($data['fee-input']) ? $data['fee-input'] : '';
    $loanStatus = $data['loanStatus'];
    $loanDuration = $data['loanDuration'];


    $stmt = $pdo->prepare("
        INSERT INTO loan_products (
            loan_product_name, access_to_branch, enable_parameters, min_principal_amount,
            default_principal_amount, max_principal_amount, interest_method, interest_type,
            loan_interest_period, loan_interest, loan_duration_period, repayment_frequency,
            num_repayments, repayment_order_list, fee_input, loan_status, loan_duration
        ) VALUES (
            :loanProductName, :accessToBranch, :enableParameters, :minPrincipalAmount,
            :defaultPrincipalAmount, :maxPrincipalAmount, :interestMethod, :interestType,
            :loanInterestPeriod, :loanInterest, :loanDurationPeriod, :repaymentFrequency,
            :numRepayments, :repaymentOrderList, :feeinput, :loanStatus, :loanDuration
        )  
    ");

    $stmt->execute([
        ':loanProductName' => $loanProductName,
        ':accessToBranch' => $accessToBranch,
        ':enableParameters' => $enableParameters,
        ':minPrincipalAmount' => $minPrincipalAmount,
        ':defaultPrincipalAmount' => $defaultPrincipalAmount,
        ':maxPrincipalAmount' => $maxPrincipalAmount,
        ':interestMethod' => $interestMethod,
        ':interestType' => $interestType,
        ':loanInterestPeriod' => $loanInterestPeriod,
        ':loanInterest' => $loanInterest,
        ':loanDurationPeriod' => $loanDurationPeriod,
        ':repaymentFrequency' => $repaymentFrequency,
        ':numRepayments' => $numRepayments,
        ':repaymentOrderList' => $repaymentOrderList,
        ':feeinput' => $feeinput,
        ':loanStatus' => $loanStatus,
        ':loanDuration' => $loanDuration
    ]);

    return [
        "success" => true,
        "message" => "Data Successfully submited!"
    ];
}
