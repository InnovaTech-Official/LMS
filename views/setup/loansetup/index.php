<?php
require_once "../../../controllers/setup/loansetup/index.php";
require_once "../../../controllers/setup/loansetup/index.php";
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

        <h1 class="main-heading">Loan Product</h1>

        <form action="../../../controllers/setup/loansetup/index.php" method="post">

            <div class="card">
                <h2 class="section-heading">Required Fields</h2>
                <div class="form-group">
                    <label for="loanProductName">Loan Product Name</label>
                    <input type="text" id="loanProductName" name="loanProductName">
                </div>
                <div class="form-group">
                    <label for="accessToBranch">Access to Branch</label>
                    <select id="accessToBranch" name="accessToBranch">
                        <option value="">Select Branch</option>
                        <option value="branchA">Branch A</option>
                        <option value="branchB">Branch B</option>
                        <option value="branchC">Branch C</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Advanced Settings (Optional)</h2>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="enableParameters" name="enableParameters">
                    <label for="enableParameters">Enable below parameters</label>
                </div>
                <div id="advancedParameters" style="display: none;">
                    <p class="placeholder-text">Additional advanced parameters will appear here when enabled.</p>
                </div>
            </div>


            <div class="card">
                <h2 class="section-heading">Principal Amount</h2>
                <div class="form-group">
                    <label for="minPrincipalAmount">Minimum Principal Amount</label>
                    <input type="text" id="minPrincipalAmount" name="minPrincipalAmount" placeholder="Minimum Amount">
                </div>
                <div class="form-group">
                    <label for="defaultPrincipalAmount">Default Principal Amount</label>
                    <input type="text" id="defaultPrincipalAmount" name="defaultPrincipalAmount" placeholder="Default Amount">
                </div>
                <div class="form-group">
                    <label for="maxPrincipalAmount">Maximum Principal Amount</label>
                    <input type="text" id="maxPrincipalAmount" name="maxPrincipalAmount" placeholder="Maximum Amount">
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Interest</h2>
                <div class="form-group">
                    <label for="interestMethod">Interest Method</label>
                    <select id="interestMethod" name="interestMethod">
                        <option value="">Select Method</option>
                        <option value="flat">Flat Rate</option>
                        <option value="reducing">Reducing Balance</option>
                        <option value="amortized">Amortized</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Interest Type</label>
                    <div class="radio-group">
                        <input type="radio" id="interestPercentage" name="interestType" value="percentage" checked>
                        <label for="interestPercentage">I want Interest to be percentage % based</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="loanInterestPeriod">Loan Interest Period</label>
                    <select id="loanInterestPeriod" name="loanInterestPeriod">
                        <option value="">Select Period</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="loanInterest">Loan Interest</label>
                    <div class="input-with-text">
                        <input type="text" id="loanInterest" name="loanInterest" placeholder="Enter Interest Rate">
                        <span class="unit-text">Per Month</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Duration</h2>
                <div class="form-group">
                    <label for="loanDurationPeriod">Loan Duration Period</label>
                    <select id="loanDurationPeriod" name="loanDurationPeriod">
                        <option value="">Select Period</option>
                        <option value="days">Days</option>
                        <option value="weeks">Weeks</option>
                        <option value="months">Months</option>
                        <option value="years">Years</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="loanDuration">Loan Duration</label>
                    <div class="input-with-text">
                        <input type="text" id="loanDuration" name="loanDuration" placeholder="Enter Duration">
                        <span class="unit-text">per month</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Repayments</h2>
                <div class="form-group">
                    <label>Repayment Frequency</label>
                    <div class="checkbox-list">
                        <div class="checkbox-group"> <input type="checkbox" id="repaymentDaily" name="repaymentFrequency[]" value="daily"> <label for="repaymentDaily">Daily</label>
                        </div>
                        <div class="checkbox-group"> <input type="checkbox" id="repaymentWeekly" name="repaymentFrequency[]" value="weekly"> <label for="repaymentWeekly">Weekly</label>
                        </div>
                        <div class="checkbox-group"> <input type="checkbox" id="repaymentBiweekly" name="repaymentFrequency[]" value="biweekly"> <label for="repaymentBiweekly">Biweekly</label>
                        </div>
                        <div class="checkbox-group"> <input type="checkbox" id="repaymentMonthly" name="repaymentFrequency[]" value="monthly"> <label for="repaymentMonthly">Monthly</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="numRepayments">Number of Repayments</label>
                    <div class="number-input-group">
                        <button type="button" class="number-btn decrement-btn">-</button>
                        <input type="number" id="numRepayments" name="numRepayments" value="0" min="0">
                        <button type="button" class="number-btn increment-btn">+</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Repayment Order:</h2>
                <p class="description-text">The order in which repayments are allocated. For example let's say you receive payment of $100 and order is <span class="highlight">Fees, Principal, Interest, Penalty</span>. Based on the loan schedule, the system will allocate the amount to Fees first and remaining amount to <span class="highlight">Principal</span> and then <span class="highlight">Interest</span> and then <span class="highlight">Penalty</span>.</p>

                <div class="repayment-order-controls">
                    <div class="form-group repayment-order-list">
                        <label for="repaymentOrderList">Repayment Order</label>
                        <select id="repaymentOrderList" multiple size="4" name="repaymentOrderList">
                            <option value="penalty">Penalty</option>
                            <option value="fees">Fees</option>
                            <option value="interest">Interest</option>
                            <option value="principal">Principal</option>
                        </select>
                    </div>
                    <div class="order-buttons">
                        <button type="button" id="moveUpBtn" class="btn secondary-btn">Up</button>
                        <button type="button" id="moveDownBtn" class="btn secondary-btn">Down</button>
                    </div>
                </div>
                <p class="note-text"><strong>Please note:</strong> If you change the above order, all Open loans will be updated with the new Repayment Order and Fee Order</p>
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
                                    <input type="text" class="fee-input" name="fee-input" placeholder="0">
                                </td>
                                <td>Deductible Fee</td>
                            </tr>
                            <tr>
                                <td>Processing Fee on MF Loan % of Due Principal Amount</td>
                                <td>
                                    <input type="text" class="fee-input" placeholder="0">
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
                        <option value="open" selected>Open</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="button-group">
                <button class="btn primary-btn" type="submit">Submit</button>
                <button class="btn secondary-btn">Cancel</button>
            </div>
        </form>




    </div>


    
  






    <script src="../../../assets/js/setup/loan_script.js"></script>
</body>

</html>