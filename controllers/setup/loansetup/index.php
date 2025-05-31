<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/loansetup/post.php");
require_once("../../../models/setup/loansetup/fetch.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    store();
} else {
    include_once("../../../views/setup/loansetup/index.php");
}

function store()
{
    $result = insertLoan($GLOBALS['pdo'], $_POST);
    if ($result['success']) {
        echo "<script>alert('Loan added successfully'); 
        window.location.href = '/setup/loansetup';</script>";
    } else {
        echo "<script>alert('Error: " . $result['message'] . "');</script>";
    }
}


