<?php
session_start();

// Define a constant to prevent direct access to view
define('CONTROLLER_ACCESS', true);

// Include model files
require_once('../../../models/setup/areasetup/fetch.php');
require_once('../../../models/setup/areasetup/post.php');
require_once('../../../models/setup/areasetup/edit.php');
require_once('../../../models/setup/areasetup/delete.php');
require_once('../../../models/setup/areasetup/city.php');

$notification = null;

// Handle AJAX requests for city operations
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    if ($_GET['action'] == 'addCity' && isset($_GET['city'])) {
        $result = addCity($_GET['city']);
        echo json_encode($result);
        exit;
    }
    elseif ($_GET['action'] == 'removeCity' && isset($_GET['city_id'])) {
        $result = removeCity($_GET['city_id']);
        echo json_encode($result);
        exit;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert Data
    if (isset($_POST['add'])) {
        $cityId = $_POST['city_id'];
        $areaName = $_POST['area'];
        
        $result = createArea($cityId, $areaName);
        $notification = [
            'title' => $result['success'] ? 'Success' : 'Error',
            'text' => $result['message'],
            'icon' => $result['success'] ? 'success' : 'error'
        ];
    }
    
    // Update Data
    elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $cityId = $_POST['city_id'];
        $areaName = $_POST['area'];
        
        $result = updateArea($id, $cityId, $areaName);
        $notification = [
            'title' => $result['success'] ? 'Success' : 'Error',
            'text' => $result['message'],
            'icon' => $result['success'] ? 'success' : 'error'
        ];
    }
    
    // Delete Data
    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        $result = deleteArea($id);
        $notification = [
            'title' => $result['success'] ? 'Success' : 'Error',
            'text' => $result['message'],
            'icon' => $result['success'] ? 'success' : 'error'
        ];
    }
}

// Get all cities for the dropdown
$cities = getAllCities();

// Get all areas for display
$areas = getAllAreas();

// Include the view
include('../../../views/setup/areasetup/index.php');
?>