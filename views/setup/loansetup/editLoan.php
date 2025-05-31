<?php
require_once "../../../controllers/setup/loansetup/edit.php";
$isEdit = isset($loan['id']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Inter:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/loan_style.css">
</head>

<body>
    <div class="container">

        <a href="../../../views/setup/loansetup/showLoan.php">Show loan Products</a>

        <h1 class="main-heading">Update Loan Product</h1>

        <form action="../../../controllers/setup/loansetup/update.php" method="post">

            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($loan['id']) ?>">
            <?php endif; ?>

            <div class="card">
                <h2 class="section-heading">Required Fields</h2>
                <div class="form-group">
                    <label for="loanProductName">Loan Product Name</label>
                    <input type="text" id="loanProductName" name="loanProductName" value="<?= htmlspecialchars($loan['loan_product_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="accessToBranch">Access to Branch</label>
                    <select id="accessToBranch" name="accessToBranch">
                        <option value="">Select Branch</option>
                        <?php foreach (['branchA', 'branchB', 'branchC'] as $branch): ?>
                            <option value="<?= $branch ?>" <?= ($loan['access_to_branch'] ?? '') === $branch ? 'selected' : '' ?>>
                                <?= ucfirst($branch) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Principal Amount</h2>
                <div class="form-group">
                    <label for="minPrincipalAmount">Minimum Principal Amount</label>
                    <input type="text" id="minPrincipalAmount" name="minPrincipalAmount" value="<?= htmlspecialchars($loan['min_principal_amount'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="defaultPrincipalAmount">Default Principal Amount</label>
                    <input type="text" id="defaultPrincipalAmount" name="defaultPrincipalAmount" value="<?= htmlspecialchars($loan['default_principal_amount'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="maxPrincipalAmount">Maximum Principal Amount</label>
                    <input type="text" id="maxPrincipalAmount" name="maxPrincipalAmount" value="<?= htmlspecialchars($loan['max_principal_amount'] ?? '') ?>">
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Interest</h2>
                <div class="form-group">
                    <label for="interestMethod">Interest Method</label>
                    <select id="interestMethod" name="interestMethod">
                        <?php foreach (['flat', 'reducing', 'amortized'] as $method): ?>
                            <option value="<?= $method ?>" <?= ($loan['interest_method'] ?? '') === $method ? 'selected' : '' ?>>
                                <?= ucfirst($method) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="loanInterest">Loan Interest</label>
                    <input type="text" id="loanInterest" name="loanInterest" value="<?= htmlspecialchars($loan['loan_interest'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="loanInterestPeriod">Loan Interest Period</label>
                    <select id="loanInterestPeriod" name="loanInterestPeriod">
                        <?php foreach (['daily', 'weekly', 'monthly', 'quarterly', 'annually'] as $period): ?>
                            <option value="<?= $period ?>" <?= ($loan['loan_interest_period'] ?? '') === $period ? 'selected' : '' ?>>
                                <?= ucfirst($period) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Duration</h2>
                <div class="form-group">
                    <label for="loanDurationPeriod">Loan Duration Period</label>
                    <select id="loanDurationPeriod" name="loanDurationPeriod">
                        <?php foreach (['days', 'weeks', 'months', 'years'] as $period): ?>
                            <option value="<?= $period ?>" <?= ($loan['loan_duration_period'] ?? '') === $period ? 'selected' : '' ?>>
                                <?= ucfirst($period) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="loanDuration">Loan Duration</label>
                    <input type="text" id="loanDuration" name="loanDuration" value="<?= htmlspecialchars($loan['loan_duration'] ?? '') ?>">
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Repayments</h2>
                <div class="form-group">
                    <label>Repayment Frequency</label>
                    <?php
                    $freqs = ['daily', 'weekly', 'biweekly', 'monthly'];
                    foreach ($freqs as $f): ?>
                        <div class="checkbox-group">
                            <input type="checkbox" id="repayment<?= ucfirst($f) ?>" name="repaymentFrequency[]" value="<?= $f;
                                                                                                                        $repaymentFrequencies = isset($loan['repayment_frequency'])
                                                                                                                            ? explode(',', $loan['repayment_frequency'])
                                                                                                                            : [];
                                                                                                                        ?>"
                                <?= in_array($f, $repaymentFrequencies) ? 'checked' : '' ?>>
                            <label for="repayment<?= ucfirst($f) ?>"><?= ucfirst($f) ?></label>

                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <label for="numRepayments">Number of Repayments</label>
                        <div class="number-input-group">
                            <button type="button" class="number-btn decrement-btn">-</button>
                            <input type="number" id="numRepayments" name="numRepayments" value="<?= htmlspecialchars($loan['num_repayments'] ?? 0) ?>" min="0">
                            <button type="button" class="number-btn increment-btn">+</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Repayment Order:</h2>
                <p class="description-text">The order in which repayments are allocated. For example let's say you receive payment of $100 and order is <span class="highlight">Fees, Principal, Interest, Penalty</span>. Based on the loan schedule, the system will allocate the amount to Fees first and remaining amount to <span class="highlight">Principal</span> and then <span class="highlight">Interest</span> and then <span class="highlight">Penalty</span>.</p>

                <div class="repayment-order-controls">
                    <div class="form-group repayment-order-list">
                        <label for="repaymentOrderList">Repayment Order</label>
                        <?php
                        $orderOptions = ['penalty', 'fees', 'interest', 'principal'];
                        $repaymentOrder = !empty($loan['repayment_order_list']) ? explode(',', $loan['repayment_order_list']) : [];
                        ?>

                        <select id="repaymentOrderList" multiple name="repaymentOrderList[]">
                            <?php foreach ($orderOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= in_array($opt, $repaymentOrder) ? 'selected' : '' ?>>
                                    <?= ucfirst($opt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="order-buttons">
                        <button type="button" id="moveUpBtn" class="btn secondary-btn">Up</button>
                        <button type="button" id="moveDownBtn" class="btn secondary-btn">Down</button>
                    </div>
                </div>
                <p class="note-text"><strong>Please note:</strong> If you change the above order, all Open loans will be updated with the new Repayment Order and Fee Order</p>

                <div class="form-group" style="margin: 8px 0px;">
                    <label>Interest Type</label>
                    <div class="radio-group">
                        <input type="radio" id="interestPercentage" name="interestType" value="percentage" checked>
                        <label for="interestPercentage">I want Interest to be percentage % based</label>
                    </div>
                </div>
            </div>


            <div class="card">
                <h2 class="section-heading">Fees:</h2>
                <div class="fees-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Value</th>
                                <th>How should Fees be charged in Loan Schedule?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Advance Service Charges % of Due Principal Amount</td>
                                <td>

                                    <input type="text" class="fee-input" name="fee-input" value="<?= htmlspecialchars($loan['fee_input'] ?? 0) ?>" placeholder="0">
                                </td>
                                <td>Deductible Fee</td>
                            </tr>
                            <tr>
                                <td>Processing Fee on MF Loan % of Due Principal Amount</td>
                                <td>
                                    <input type="text" class="fee-input" value="<?= htmlspecialchars($loan['fee_input'] ?? 0) ?>" placeholder="0">
                                </td>
                                <td>Deductible Fee</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Loan Status:</h2>
                <div class="info-box">
                    <p>Below, you can choose the <span class="highlight">Loan Status</span> option that should be auto-selected on the <span class="highlight">Add Loan</span> page. If you leave the below empty, the <span class="highlight">Open</span> status will be selected by default for a new loan. But you may want another status such as the <span class="highlight">Processing</span> status to be auto-selected for new loans.</p>
                </div>
                <div class="form-group">
                    <label for="loanStatusDropdown">Loan Status</label>

                    <select id="loanStatusDropdown" name="loanStatus">
                        <?php
                        foreach (['open', 'pending', 'processing', 'approved', 'rejected'] as $status):
                            $selected = ($loan['loan_status'] ?? '') === $status ? 'selected' : '';
                        ?>
                            <option value="<?= $status ?>" <?= $selected ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="button-group">
                <button class="btn primary-btn" type="submit">Update</button>
                <a class="btn secondary-btn" href="">Cancel</a>
            </div>
        </form>





    </div>










    <script src="../../../assets/js/setup/loan_script.js"></script>
</body>

</html>