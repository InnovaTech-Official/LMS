document.addEventListener('DOMContentLoaded', function () {
    const branchOpenDateInput = document.getElementById('branchOpenDate');
    if (branchOpenDateInput) {
        flatpickr(branchOpenDateInput, {
            dateFormat: "m/d/y",
        });
    }

    const submitBtn = document.querySelector('.primary-btn');

    const cancelBtn = document.querySelector('.secondary-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function (event) {
            event.preventDefault();
            console.log('Cancel button clicked. Resetting form or navigating back.');

            document.getElementById('branchName').value = '';
            document.getElementById('branchOpenDate').value = '';
            document.getElementById('country').value = 'pakistan';
            document.getElementById('currency').value = 'pkr';
            document.getElementById('dateFormat').value = 'dd/mm/yyyy';
            document.getElementById('currencyInWords').value = 'Pak Rupee';
        });
    }



    const branchSetupForm = document.getElementById('branchSetupForm');
    if (branchSetupForm) {
        branchSetupForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const branchName = document.getElementById('branchName').value;
            const branchOpenDate = document.getElementById('branchOpenDate').value;
            const country = document.getElementById('country').value;
            const currency = document.getElementById('currency').value;
            const dateFormat = document.getElementById('dateFormat').value;
            const currencyInWords = document.getElementById('currencyInWords').value;
            const branchAddress = document.getElementById('branchAddress').value;
            const branchMobile = document.getElementById('branchMobile').value;
            const branchCity = document.getElementById('branchCity').value;
            const branchProvince = document.getElementById('branchProvince').value;
            const borrowerUniqueNumber = document.getElementById('borrowerUniqueNumber').value;
            const loanUniqueNumber = document.getElementById('loanUniqueNumber').value;
            
            const formData = {
                branchName,
                branchOpenDate,
                country,
                currency,
                dateFormat,
                currencyInWords,
                branchAddress,
                branchMobile,
                branchCity,
                branchProvince,
                borrowerUniqueNumber,
                loanUniqueNumber
            };
            fetch('../../../controllers/setup/branchsetup/index.php', {
                method: 'POST',
                body: JSON.stringify(formData),
            }).then(data => data.json())
            .then((response) => {
                    const successMessage = document.getElementById('successMessage');
                    console.log('Branch setup successful:', response);
                    successMessage.innerHTML = '';
                    successMessage.style.display = 'block';
                    branchSetupForm.reset();
                    successMessage.innerHTML = response.message;
                }).catch((error) => {
                    console.error('Error during branch setup:', error);
                });
        });
    }












});