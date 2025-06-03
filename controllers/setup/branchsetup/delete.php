<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/branchsetup/delete.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'] ?? null;

    if ($id !== null) {
        $branch = deleteId($id, $GLOBALS['pdo']);
        echo json_encode($branch);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No ID provided.'
        ]);
    }
    exit;
}
