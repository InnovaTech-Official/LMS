<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Branch</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Inter:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/branchSetup.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <div class="container">

        <a href="../../../views/setup/branchsetup/showBranch.php">Show Branches</a>
        <h1 class="main-heading">Branch Setup</h1>

        <form method="post" id="branchUpdateForm">
            <div class="card">
                <h2 class="section-heading">Required Fields:</h2>
                <div class="form-group">
                    <label for="branchName">Branch Name</label>
                    <input type="text" id="branchName" name="branchName" placeholder="Branch Name">
                    <input type="hidden" name="id" id="branchId">

                </div>
                <div class="form-group">
                    <label for="branchOpenDate">Branch Open Date</label>
                    <input type="text" id="branchOpenDate" name="branchOpenDate" placeholder="mm/dd/yy" class="flatpickr-input">
                </div>
            </div>


            <div class="card">
                <h2 class="section-heading">Currency & Date Format:</h2>
                <p class="description-text">If you are operating in different countries, you can create a branch for each country and then override the account settings below. This is particularly useful for setting different currencies for each branch.</p>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="pakistan" selected>Pakistan</option>
                        <option value="usa">USA</option>
                        <option value="uk">UK</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency">
                        <option value="pkr" selected>PKR - Rs</option>
                        <option value="usd">USD - $</option>
                        <option value="gbp">GBP - £</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dateFormat">Date Format</label>
                    <select id="dateFormat" name="dateFormat">
                        <option value="dd/mm/yyyy" selected>dd/mm/yyyy</option>
                        <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                        <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="currencyInWords">Currency in Words</label>
                    <input type="text" id="currencyInWords" name="currencyInWords" placeholder="Pak Rupee" value="Pak Rupee">
                </div>
                <p class="note-text">
                    Please visit <a href="#" class="highlight-link">Account Settings</a> for an explanation on Currency in Words field.
                </p>
            </div>


            <div class="card">
                <h2 class="section-heading">Optional Fields: Address</h2>
                <div class="form-group">
                    <label for="branchAddress">Branch Address</label>
                    <input type="text" id="branchAddress" name="branchAddress" placeholder="Branch Address">
                </div>
                <div class="form-group">
                    <label for="branchCity">Branch City</label>
                    <input type="text" id="branchCity" name="branchCity" placeholder="Branch City">
                </div>
                <div class="form-group">
                    <label for="branchProvince">Branch Province</label>
                    <input type="text" id="branchProvince" name="branchProvince" placeholder="Branch Province">
                </div>
                <div class="form-group">
                    <label for="branchMobile">Branch Mobile</label>
                    <input type="text" id="branchMobile" name="branchMobile" placeholder="Numbers only">
                </div>
            </div>

            <div class="card">
                <h2 class="section-heading">Optional Fields: Generate custom Borrower Unique Numbers and Loan Unique Numbers</h2>
                <p class="description-text">
                    Below, you can generate a <span class="highlight">Borrower Unique Number</span> on the <span class="highlight">Add Borrower</span> page and a unique <span class="highlight">Loan #</span> on the <span class="highlight">Add Loan</span> page. If you do not set the below, for borrowers, the system leaves the <span class="highlight">Borrower Unique Number</span> field empty. For loans, the system generates a unique 7 digit Loan #. But you might want a different format or a branch code in that number. You can use the below placeholders to force the system into generating borrower unique numbers and loan numbers based on the below patterns.
                </p>
                <h3 class="subsection-heading">Examples</h3>
                <ul class="example-list">
                    <li>To generate BR-10001, BR-10002 and so on, you would type <span class="code-text">BR-[U10001]</span> below.</li>
                    <li>To generate LR-Year-Month-10001, LR-Year-Month-10002 and so on, you would type <span class="code-text">LR-{yyyy}-{mm}-[U10001]</span> below.</li>
                    <li>To generate 10001-YearMonthDay, 10002-YearMonthDay and so on, you would type <span class="code-text">{U10001}-{yyyymmdd}</span> or <span class="code-text">{U10001}-{yyymmdd}</span> below.</li>
                    <li>To generate LRO-10-BR-11111, LRO-10-BR-11112 and so on, you would type <span class="code-text">LRO-10-BR-{U11111}</span> below.</li>
                    <li>To generate LRO10BR11111, LRO10BR11112 and so on, you would type <span class="code-text">LRO10BR{U11111}</span> below.</li>
                </ul>

                <h2 class="section-heading-red">Enter the placeholders for:</h2>
                <div class="form-group">
                    <label for="borrowerUniqueNumber">Borrower Unique Number (optional)</label>
                    <input type="text" id="borrowerUniqueNumber" name="borrowerUniqueNumber">
                    <p class="description-text-small">Leave it empty if you do not need a Borrower Unique Number</p>
                </div>
                <div class="form-group">
                    <label for="loanUniqueNumber">Loan Unique Number (optional)</label>
                    <input type="text" id="loanUniqueNumber" name="loanUniqueNumber">
                    <p class="description-text-small">Leave it empty if you would like the system to auto generate this</p>
                </div>

                <div class="button-group">
                    <button class="btn primary-btn">Submit</button>
                    <button class="btn secondary-btn">Cancel</button>
                </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const branchId = urlParams.get('id');

            if (!branchId) return;
            fetch(`../../../controllers/setup/branchsetup/editBranch.php?id=${branchId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const branch = data.data;
                        document.getElementById('branchId').value = branch.id;
                        document.getElementById('branchName').value = branch.branch_name || '';
                        document.getElementById('branchOpenDate').value = branch.branch_open_date || '';
                        document.getElementById('country').value = branch.country || 'pakistan';
                        document.getElementById('currency').value = branch.currency || 'pkr';
                        document.getElementById('dateFormat').value = branch.date_format || 'dd/mm/yyyy';
                        document.getElementById('currencyInWords').value = branch.currency_in_words || '';
                        document.getElementById('branchAddress').value = branch.branch_address || '';
                        document.getElementById('branchCity').value = branch.branch_city || '';
                        document.getElementById('branchProvince').value = branch.branch_province || '';
                        document.getElementById('branchMobile').value = branch.branch_mobile || '';
                        document.getElementById('borrowerUniqueNumber').value = branch.borrower_unique_number || '';
                        document.getElementById('loanUniqueNumber').value = branch.loan_unique_number || '';

                        if (typeof flatpickr !== 'undefined') {
                            flatpickr("#branchOpenDate", {
                                defaultDate: branch.branch_open_date
                            });
                        }
                    } else {
                        alert('Failed to load branch data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong while fetching branch data.');
                });

            const branchUpdateForm = document.getElementById('branchUpdateForm');
            if (branchUpdateForm) {
                branchUpdateForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = {
                        branchName: document.getElementById('branchName').value,
                        branchOpenDate: document.getElementById('branchOpenDate').value,
                        country: document.getElementById('country').value,
                        currency: document.getElementById('currency').value,
                        dateFormat: document.getElementById('dateFormat').value,
                        currencyInWords: document.getElementById('currencyInWords').value,
                        branchAddress: document.getElementById('branchAddress').value,
                        branchMobile: document.getElementById('branchMobile').value,
                        branchCity: document.getElementById('branchCity').value,
                        branchProvince: document.getElementById('branchProvince').value,
                        borrowerUniqueNumber: document.getElementById('borrowerUniqueNumber').value,
                        loanUniqueNumber: document.getElementById('loanUniqueNumber').value,
                        id: document.getElementById('branchId').value 
                    };

                    console.log(formData.id);

                    fetch("../../../controllers/setup/branchsetup/editBranch.php", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(formData),
                        })
                        .then(res => res.json())
                        .then((response) => {
                            if (response.success) {
                                alert('Branch Update successful');
                                window.location.href = '../../../views/setup/branchsetup/showBranch.php';
                            } else {
                                alert('Error: ' + response.message);
                            }
                        })
                        .catch((error) => {
                            console.error('Error during branch setup:', error);
                        });
                });
            }




        });
    </script>
</body>

</html>