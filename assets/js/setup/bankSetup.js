// Bank Setup JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Function to open the edit modal
    window.openEditModal = function(id) {
        // Get the bank account data from the table row
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;
        
        // Set the form values
        document.getElementById('edit_bank_account_id').value = id;
        document.getElementById('edit_bank_name').value = row.cells[0].textContent;
        document.getElementById('edit_branch_name').value = row.cells[1].textContent;
        document.getElementById('edit_branch_code').value = row.cells[2].textContent;
        document.getElementById('edit_account_number').value = row.cells[3].textContent;
        document.getElementById('edit_account_title').value = row.cells[4].textContent;
        document.getElementById('edit_iban').value = row.cells[5].textContent;
        document.getElementById('edit_swift_code').value = row.cells[6].textContent;
        document.getElementById('edit_bank_address').value = row.cells[7].textContent;
        document.getElementById('edit_contact_person').value = row.cells[8].textContent;
        document.getElementById('edit_contact_number').value = row.cells[9].textContent;
        document.getElementById('edit_email').value = row.cells[10].textContent;
        
        // Show the modal
        document.getElementById('editModal').style.display = 'block';
    };
    
    // Function to close the edit modal
    window.closeEditModal = function() {
        document.getElementById('editModal').style.display = 'none';
    };
    
    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            closeEditModal();
        }
    };
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length) {
        setTimeout(function() {
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    }
});