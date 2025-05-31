document.addEventListener('DOMContentLoaded', function() {
    const enableParametersCheckbox = document.getElementById('enableParameters');
    const advancedParametersDiv = document.getElementById('advancedParameters');
    const interestPercentageRadio = document.getElementById('interestPercentage');
    const loanInterestField = document.getElementById('loanInterest');
    const numRepaymentsInput = document.getElementById('numRepayments');
    const decrementBtn = document.querySelector('.decrement-btn');
    const incrementBtn = document.querySelector('.increment-btn');

    // Function to toggle visibility of advanced parameters
    function toggleAdvancedParameters() {
        if (enableParametersCheckbox.checked) {
            advancedParametersDiv.style.display = 'block';
        } else {
            advancedParametersDiv.style.display = 'none';
        }
    }

    // Set initial state for advanced parameters
    toggleAdvancedParameters();

    // Add event listener for checkbox change
    enableParametersCheckbox.addEventListener('change', toggleAdvancedParameters);

    // Function to handle changes in the Interest Type radio button
    function handleInterestTypeChange() {
        if (interestPercentageRadio.checked) {
            console.log("Interest is percentage-based.");
            loanInterestField.placeholder = "Enter Percentage (%)";
        } else {
            console.log("Another interest type might be selected or none.");
            loanInterestField.placeholder = "Enter Interest Rate";
        }
    }

    // Add event listener to the radio button and set initial state
    interestPercentageRadio.addEventListener('change', handleInterestTypeChange);
    handleInterestTypeChange(); // Call once on load

    // Number of Repayments Increment/Decrement
    decrementBtn.addEventListener('click', function() {
        let currentValue = parseInt(numRepaymentsInput.value);
        if (currentValue > parseInt(numRepaymentsInput.min)) {
            numRepaymentsInput.value = currentValue - 1;
        }
    });

    incrementBtn.addEventListener('click', function() {
        let currentValue = parseInt(numRepaymentsInput.value);
        numRepaymentsInput.value = currentValue + 1;
    });

    // Repayment Order Up/Down Functionality to select next/previous item
    const repaymentOrderList = document.getElementById('repaymentOrderList');
    const moveUpBtn = document.getElementById('moveUpBtn');
    const moveDownBtn = document.getElementById('moveDownBtn');

    function selectNextOption(direction) {
        let selectedIndex = repaymentOrderList.selectedIndex;
        const optionsCount = repaymentOrderList.options.length;

        if (direction === 'up') {
            if (selectedIndex > 0) {
                repaymentOrderList.selectedIndex = selectedIndex - 1;
            } else if (selectedIndex === 0 && optionsCount > 0) {
                repaymentOrderList.selectedIndex = optionsCount - 1;
            }
        } else if (direction === 'down') {
            if (selectedIndex < optionsCount - 1) {
                repaymentOrderList.selectedIndex = selectedIndex + 1;
            } else if (selectedIndex === optionsCount - 1 && optionsCount > 0) {
                repaymentOrderList.selectedIndex = 0;
            } else if (selectedIndex === -1 && optionsCount > 0) {
                repaymentOrderList.selectedIndex = 0;
            }
        }
    }

    moveUpBtn.addEventListener('click', () => selectNextOption('up'));
    moveDownBtn.addEventListener('click', () => selectNextOption('down'));

    
    const repaymentFrequencyCheckboxes = document.querySelectorAll('input[name="repaymentFrequency"]');

    repaymentFrequencyCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // If this checkbox is checked, uncheck all others in the group
            if (this.checked) {
                repaymentFrequencyCheckboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        });
    });
});