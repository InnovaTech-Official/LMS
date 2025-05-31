document.addEventListener('DOMContentLoaded', function() {

    const branchOpenDateInput = document.getElementById('branchOpenDate');
    if (branchOpenDateInput) {
        flatpickr(branchOpenDateInput, {
            dateFormat: "m/d/y", 
        });
    }


    const submitBtn = document.querySelector('.primary-btn'); 
    if (submitBtn) {
        submitBtn.addEventListener('click', function(event) {
            event.preventDefault();

            const branchName = document.getElementById('branchName').value;
            const branchOpenDate = document.getElementById('branchOpenDate').value;

            
            const country = document.getElementById('country').value;
            const currency = document.getElementById('currency').value;
            const dateFormat = document.getElementById('dateFormat').value;
            const currencyInWords = document.getElementById('currencyInWords').value;

            console.log('Submitting Branch Setup Details:');
            console.log('Branch Name:', branchName);
            console.log('Branch Open Date:', branchOpenDate);
            console.log('Country:', country);
            console.log('Currency:', currency);
            console.log('Date Format:', dateFormat);
            console.log('Currency in Words:', currencyInWords);

            alert('Branch Setup details submitted (check console for values)!');

        });
    }

    
    const cancelBtn = document.querySelector('.secondary-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(event) {
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
});