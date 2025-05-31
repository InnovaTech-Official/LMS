<?php
require_once(__DIR__ . '/../../../includes/db_wrapper.php');
require_once(__DIR__ . '/../../../includes/local_config.php');
require_once("../../../models/setup/loansetup/fetch.php");


function getData(){
    $data = fetchData($GLOBALS['pdo']);
    return $data;
}

?>