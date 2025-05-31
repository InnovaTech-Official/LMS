<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die("Invalid request. No ID provided.");
    }

    try {
        $stmt = $pdo->prepare("
    UPDATE loan_products SET
        loan_product_name = :loanProductName,
        access_to_branch = :accessToBranch,
        min_principal_amount = :minPrincipalAmount,
        default_principal_amount = :defaultPrincipalAmount,
        max_principal_amount = :maxPrincipalAmount,
        interest_method = :interestMethod,
        interest_type = :interestType,
        loan_interest_period = :loanInterestPeriod,
        loan_interest = :loanInterest,
        loan_duration_period = :loanDurationPeriod,
        loan_duration = :loanDuration,
        num_repayments = :numRepayments,
        repayment_frequency = :repaymentFrequency,
        repayment_order_list = :repaymentOrderList,
        loan_status = :loanStatus,
        fee_input = :feeinput
    WHERE id = :id
");


        $stmt->execute([
            ':loanProductName' => $_POST['loanProductName'] ?? '',
            ':accessToBranch' => $_POST['accessToBranch'] ?? '',
            ':minPrincipalAmount' => $_POST['minPrincipalAmount'] ?? 0,
            ':defaultPrincipalAmount' => $_POST['defaultPrincipalAmount'] ?? 0,
            ':maxPrincipalAmount' => $_POST['maxPrincipalAmount'] ?? 0,
            ':interestMethod' => $_POST['interestMethod'] ?? '',
            ':interestType' => $_POST['interestType'] ?? '',
            ':loanInterestPeriod' => $_POST['loanInterestPeriod'] ?? '',
            ':loanInterest' => $_POST['loanInterest'] ?? 0,
            ':loanDurationPeriod' => $_POST['loanDurationPeriod'] ?? '',
            ':loanDuration' => $_POST['loanDuration'] ?? '',
            ':numRepayments' => $_POST['numRepayments'] ?? 0,
            ':loanStatus' => $_POST['loanStatus'] ?? 'open',
            ':repaymentFrequency' => isset($_POST['repaymentFrequency']) ? implode(',', $_POST['repaymentFrequency']) : '',
            ':repaymentOrderList' => isset($_POST['repaymentOrderList']) ? implode(',', $_POST['repaymentOrderList']) : '',
            ':feeinput' => $_POST['fee-input'] ?? '',

            ':id' => $id
        ]);

        $_SESSION['success_message'] = "Loan updated successfully.";
        header("Location: ../../../views/setup/loansetup/showLoan.php");
        exit;
    } catch (Exception $e) {
        echo "Error updating loan: " . $e->getMessage();
    }
}
