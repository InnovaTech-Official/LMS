<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/loansetup/delete.php");

$id = $_GET['id'] ?? null;
if ($id !== null) {
    $loan = deleteId($id, $GLOBALS['pdo']);
    include('../../../views/setup/loansetup/showLoan.php');
} else {
    echo "No ID provided.";
    exit;
}
