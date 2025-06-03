<?php
include_once(__DIR__ . "../../../../includes/local_config.php");
include_once(__DIR__ . "../../../../models/setup/branchsetup/post.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once('../../../views/setup/branchsetup/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        exit;
    }
    $response = insertBranch($pdo, $data);
    if ($response) {
        echo json_encode(['status' => 'success', 'message' => 'Branch created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create branch']);
    }
}
