function openEditModal(id) {
    // Fetch the data for the selected bank account
    const row = document.querySelector(`tr[data-id='${id}']`);
    document.getElementById('edit_bank_account_id').value = id;
    document.getElementById('edit_bank_name').value = row.children[0].innerText;
    document.getElementById('edit_branch_name').value = row.children[1].innerText;
    document.getElementById('edit_branch_code').value = row.children[2].innerText;
    document.getElementById('edit_account_number').value = row.children[3].innerText;
    document.getElementById('edit_account_title').value = row.children[4].innerText;
    document.getElementById('edit_iban').value = row.children[5].innerText;
    document.getElementById('edit_swift_code').value = row.children[6].innerText;
    document.getElementById('edit_bank_address').value = row.children[7].innerText;
    document.getElementById('edit_contact_person').value = row.children[8].innerText;
    document.getElementById('edit_contact_number').value = row.children[9].innerText;
    document.getElementById('edit_email').value = row.children[10].innerText;

    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this bank account?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete';
        input.value = 'true';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'delete_id';
        idInput.value = id;
        
        form.appendChild(input);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}