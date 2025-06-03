<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/branchsetup/edit.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id !== null) {
        $branch = getId($id, $GLOBALS['pdo']);
        echo json_encode($branch);
        exit;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No ID provided.'
        ]);
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Branch ID is required']);
        exit;
    }

    try {
        $result = updateBranch($pdo, $data);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error updating branch: ' . $e->getMessage()
        ]);
    }
    exit;
}
