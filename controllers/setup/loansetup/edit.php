<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/loansetup/edit.php");

$id = $_GET['id'] ?? null;
if ($id !== null) {
    $loan = getId($id, $GLOBALS['pdo']);
    if (!$loan) {
        echo "Loan Not Found";
        exit;
    }
}else {
    echo "No ID provided.";
    exit;
}
