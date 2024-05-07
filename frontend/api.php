<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deals_json = file_get_contents("/deals/deals-" . date('Y-m-d') . ".json");

header("Content-Type: application/json");
echo $deals_json;