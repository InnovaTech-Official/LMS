<?php
session_start();
include_once("../../../controllers/setup/loansetup/showLoan.php")
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Show Loan Products</title>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">

                <div class="mb-4">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success_message'] ?>
                        </div>
                        <?php unset($_SESSION['success_message']); 
                        ?>
                    <?php endif; ?>

                </div>

                <?php
                $dataResult = getData();
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Loan Product Name</th>
                                <th>Access To Branch</th>
                                <th>Enable Parameters</th>
                                <th>Min Principal Amount</th>
                                <th>Default Principal Amount</th>
                                <th>Max Principal Amount</th>
                                <th>Interest Method</th>
                                <th>Interest Type</th>
                                <th>Loan Interest Period</th>
                                <th>Loan Interest</th>
                                <th>Loan Duration Period</th>
                                <th>Loan Duration</th>
                                <th>Repayment Frequency</th>
                                <th>Number of Repayments</th>
                                <th>Repayment Order List</th>
                                <th>Fee Input</th>
                                <th>Loan Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $id = 1;
                            if ($dataResult['success'] && !empty($dataResult['data'])): ?>
                                <?php foreach ($dataResult['data'] as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_product_name']) ?></td>
                                        <td><?= htmlspecialchars($row['access_to_branch']) ?></td>
                                        <td><?= $row['enable_parameters'] ? 'Yes' : 'No' ?></td>
                                        <td><?= htmlspecialchars($row['min_principal_amount']) ?></td>
                                        <td><?= htmlspecialchars($row['default_principal_amount']) ?></td>
                                        <td><?= htmlspecialchars($row['max_principal_amount']) ?></td>
                                        <td><?= htmlspecialchars($row['interest_method']) ?></td>
                                        <td><?= htmlspecialchars($row['interest_type']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_interest_period']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_interest']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_duration_period']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_duration']) ?></td>
                                        <td><?= htmlspecialchars($row['repayment_frequency']) ?></td>
                                        <td><?= htmlspecialchars($row['num_repayments']) ?></td>
                                        <td><?= htmlspecialchars($row['repayment_order_list'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['fee_input']) ?></td>
                                        <td><?= htmlspecialchars($row['loan_status']) ?></td>
                                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                                        <td>
                                            <a href="../../../controllers/setup/loansetup/edit.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-warning">Edit</a>
                                            <a href="../../../controllers/setup/loansetup/delete.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="18">No data found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>