<?php
// No direct access
if (!defined('CONTROLLER_ACCESS')) {
    header('Location: ../../../dashboard/pos_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LMS - Area Setup</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="../../../assets/css/areaSetup.css">
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <!-- Show notification if exists -->
    <?php if (isset($notification)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<?php echo $notification['title']; ?>',
                text: '<?php echo $notification['text']; ?>',
                icon: '<?php echo $notification['icon']; ?>',
                confirmButtonText: 'OK'
            });
        });
    </script>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div>
        <a href="../../../controllers/dashboard/dashboard.php" class="dashboard-link">Back to Dashboard</a>
    </div>

    <div class="form-container">
        <h2>Area Setup</h2>
        <form method="POST" id="areaForm" action="index.php">
            <input type="hidden" name="id" id="id">
            
            <div class="form-group">
                <label>City:</label>
                <div class="city-container">
                    <select class="city-select" name="city_id" id="city_id" required>
                        <option value="">Select a city</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo htmlspecialchars($city['id']); ?>"><?php echo htmlspecialchars($city['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-add-city" id="addNewCity">+</button>
                    <button type="button" class="btn-remove-city" id="removeCity">-</button>
                </div>
            </div>
            
            <div class="form-group">
                <label>Area:</label>
                <input type="text" name="area" id="area" required>
            </div>
            
            <button type="submit" name="add">Add Area</button>
            <button type="submit" name="update" id="updateBtn" style="display:none;">Update</button>
        </form>
    </div>

    <h3>Existing Areas</h3>
    <table>
        <tr>
            <th>City</th>
            <th>Area</th>
            <th>Actions</th>
        </tr>
        <?php
        if (isset($areas) && !isset($areas['error'])) {
            if (count($areas) > 0) {
                foreach ($areas as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["city_name"]) . "</td>
                            <td>" . htmlspecialchars($row["area_name"]) . "</td>
                            <td>
                                <button onclick='editArea(" . $row["id"] . ", " . $row["city_id"] . ", \"" . htmlspecialchars($row["area_name"]) . "\", \"" . htmlspecialchars($row["city_name"]) . "\")'>Edit</button>
                                <button class='delete-btn' onclick='deleteArea(" . $row["id"] . ")'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No areas found</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Error fetching data: " . (isset($areas['error']) ? $areas['error'] : "Unknown error") . "</td></tr>";
        }
        ?>
    </table>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for searchable dropdown
            $('.city-select').select2({
                placeholder: "Select a city",
                allowClear: true
            });
            
            // Handle Add New City button click
            $('#addNewCity').click(function() {
                Swal.fire({
                    title: 'Add New City',
                    input: 'text',
                    inputPlaceholder: 'Enter city name',
                    showCancelButton: true,
                    confirmButtonText: 'Add',
                    showLoaderOnConfirm: true,
                    preConfirm: (city) => {
                        if (!city) {
                            Swal.showValidationMessage('City name cannot be empty');
                            return false;
                        }
                        return fetch(`index.php?action=addCity&city=${encodeURIComponent(city)}`, {
                            method: 'GET'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Add the new city to the dropdown
                                var newOption = new Option(city, data.id, true, true);
                                $('.city-select').append(newOption).trigger('change');
                                return data;
                            } else {
                                throw new Error(data.message || 'Failed to add city');
                            }
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.value.message,
                            icon: 'success'
                        });
                    }
                });
            });
            
            // Handle Remove City button click
            $('#removeCity').click(function() {
                var selectedCityId = $('.city-select').val();
                var selectedCityName = $('.city-select option:selected').text();
                
                if (!selectedCityId) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select a city to remove',
                        icon: 'error'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to delete the city "${selectedCityName}" and all associated areas?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`index.php?action=removeCity&city_id=${encodeURIComponent(selectedCityId)}`, {
                            method: 'GET'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the city from the dropdown
                                $('.city-select option[value="' + selectedCityId + '"]').remove();
                                $('.city-select').trigger('change');
                                
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    // Refresh the page to update the table
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to remove city: ' + error.message,
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });

        // Function to edit area
        function editArea(id, cityId, areaName, cityName) {
            document.getElementById('id').value = id;
            document.getElementById('area').value = areaName;
            
            // Set the city in Select2
            $('#city_id').val(cityId).trigger('change');
            
            document.getElementById('updateBtn').style.display = 'inline-block';
            document.querySelector('button[name="add"]').style.display = 'none';
        }

        // Function to delete area with confirmation
        function deleteArea(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this area!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form to delete the area
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = id;
                    form.appendChild(input);
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete';
                    deleteInput.value = 'true';
                    form.appendChild(deleteInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>