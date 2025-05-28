// Chart of Accounts JavaScript
// Base URL for controller requests
const CONTROLLER_URL = '../../../controllers/setup/coa/index.php';

// Search functionality for each list
function performSearch(type) {
    const searchInput = document.getElementById(`search${type}`);
    const value = searchInput.value.toLowerCase();
    const listId = `${type.toLowerCase()}List`;
    const items = document.querySelectorAll(`#${listId} li`);
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(value) ? '' : 'none';
    });
}

function cancelSearch(type) {
    const searchInput = document.getElementById(`search${type}`);
    searchInput.value = '';
    const listId = `${type.toLowerCase()}List`;
    const items = document.querySelectorAll(`#${listId} li`);
    items.forEach(item => {
        item.style.display = '';
    });
}

// Edit functionality
function openEditModal(id, type, name) {
    const modal = document.getElementById('editModal');
    const editIdInput = document.getElementById('edit_id');
    const editTypeInput = document.getElementById('edit_type');
    const editNameInput = document.getElementById('edit_name');
    
    editIdInput.value = id;
    editTypeInput.value = type;
    editNameInput.value = name;
    
    modal.style.display = 'block';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    ['Heads', 'Subs', 'Accounts'].forEach(type => {
        const searchInput = document.getElementById(`search${type}`);
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch(type);
                } else if (e.key === 'Escape') {
                    cancelSearch(type);
                }
            });
        }
    });
});

let deleteId = '';
let deleteType = '';

function showDeleteConfirmation(id, type, name) {
    console.log('Checking dependencies for:', id, type, name);
    deleteId = id;
    deleteType = type;
    
    // First check for dependencies
    const formData = new FormData();
    formData.append('check_dependencies', '1');
    formData.append('delete_id', id);
    formData.append('delete_type', type);
    
    fetch(CONTROLLER_URL, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        const modal = document.getElementById('deleteConfirmationModal');
        const confirmText = document.getElementById('deleteConfirmationText');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');

        if (!modal || !confirmText || !confirmBtn || !cancelBtn) {
            console.error('Modal elements not found');
            return;
        }

        if (data.has_dependencies) {
            confirmText.innerHTML = data.error;
            confirmBtn.style.display = 'none';
            cancelBtn.textContent = 'Close';
        } else {
            let itemType = type.replace('_', ' ');
            confirmText.textContent = `Are you sure you want to delete this ${itemType}: ${name}?`;
            confirmBtn.style.display = 'inline-block';
            cancelBtn.textContent = 'Cancel';
        }
        
        modal.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        const modal = document.getElementById('deleteConfirmationModal');
        const confirmText = document.getElementById('deleteConfirmationText');
        
        if (!modal || !confirmText) {
            console.error('Modal elements not found');
            return;
        }

        confirmText.textContent = 'An error occurred while checking dependencies. Please try again.';
        if (document.getElementById('confirmDeleteBtn')) {
            document.getElementById('confirmDeleteBtn').style.display = 'none';
        }
        if (document.getElementById('cancelDeleteBtn')) {
            document.getElementById('cancelDeleteBtn').textContent = 'Close';
        }
        modal.style.display = 'block';
    });
}

function confirmDelete() {
    console.log('Confirming delete for:', deleteId, deleteType);
    const formData = new FormData();
    formData.append('delete_id', deleteId);
    formData.append('delete_type', deleteType);
    
    fetch(CONTROLLER_URL, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Delete response:', data);
        if (data.success) {
            location.reload();
        } else {
            const confirmText = document.getElementById('deleteConfirmationText');
            if (confirmText) {
                confirmText.textContent = data.error || 'Failed to delete item';
                if (document.getElementById('confirmDeleteBtn')) {
                    document.getElementById('confirmDeleteBtn').style.display = 'none';
                }
                if (document.getElementById('cancelDeleteBtn')) {
                    document.getElementById('cancelDeleteBtn').textContent = 'Close';
                }
            }
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        const confirmText = document.getElementById('deleteConfirmationText');
        if (confirmText) {
            confirmText.textContent = 'An error occurred while deleting. Please try again.';
            if (document.getElementById('confirmDeleteBtn')) {
                document.getElementById('confirmDeleteBtn').style.display = 'none';
            }
            if (document.getElementById('cancelDeleteBtn')) {
                document.getElementById('cancelDeleteBtn').textContent = 'Close';
            }
        }
    });
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmationModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteConfirmationModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
    
    const editModal = document.getElementById('editModal');
    if (event.target == editModal) {
        closeEditModal();
    }
}