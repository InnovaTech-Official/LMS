<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Show Branches</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">branchName</th>
                                <th scope="col">branchOpenDate</th>
                                <th scope="col">country</th>
                                <th scope="col">currency</th>
                                <th scope="col">dateFormat</th>
                                <th scope="col">currencyInWords</th>
                                <th scope="col">branchAddress</th>
                                <th scope="col">branchCity</th>
                                <th scope="col">branchProvince</th>
                                <th scope="col">branchMobile</th>
                                <th scope="col">borrowerUniqueNumber</th>
                                <th scope="col">loanUniqueNumber</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="branchList">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        const fetchData = () => {
            fetch('../../../controllers/setup/branchsetup/showBranch.php', {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const branches = data.data;
                        const branchList = document.getElementById('branchList');
                        branchList.innerHTML = '';

                        branches.forEach((d, index) => {
                            branchList.innerHTML += `
        <tr>
            <td>${index + 1}</td>
            <td>${d.branch_name}</td>
            <td>${d.branch_open_date}</td>
            <td>${d.country}</td>
            <td>${d.currency}</td>
            <td>${d.date_format}</td>
            <td>${d.currency_in_words}</td>
            <td>${d.branch_address}</td>
            <td>${d.branch_mobile}</td>
            <td>${d.branch_city}</td>
            <td>${d.branch_province}</td>
            <td>${d.borrower_unique_number}</td>
            <td>${d.loan_unique_number}</td>
            <td>
                <button class="btn btn-warning editBtn" data-id="${d.id}">Edit</button>
                <button class="btn btn-danger deleteBtn" data-id="${d.id}">Delete</button>
            </td>
        </tr>
    `;
                        });

                    } else {
                        console.error('Failed to fetch branch data:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching branch data:', error);
                });
        }
        fetchData();
        document.getElementById('branchList').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('editBtn')) {
                event.preventDefault();
                const branchId = event.target.getAttribute('data-id');
                window.location.href = `../../../views/setup/branchsetup/edit.php?id=${branchId}`;
            };

            if (event.target && event.target.classList.contains('deleteBtn')) {
                console.log("Hello")
                event.preventDefault();
                const branchId = event.target.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this branch?')) {
                    fetch(`../../../controllers/setup/branchsetup/delete.php?id=${branchId}`, {
                            method: 'POST',
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success === true) {
                                window.location.href = '../../../views/setup/branchsetup/showBranch.php';
                                fetchData();
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting branch:', error);
                        });
                }

            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>