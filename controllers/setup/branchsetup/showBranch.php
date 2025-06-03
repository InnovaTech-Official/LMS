<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/branchsetup/fetch.php");

header('Content-Type: application/json');

$data = fetchData($pdo);
echo json_encode($data);
exit;