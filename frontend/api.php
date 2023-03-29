<?php

#$json = file_get_contents("https://gianklug.com/alldeals/backend/index.php");
#$json = shell_exec("../backend/api.py");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deals_json = file_get_contents("/deals/deals-" . date('Y-m-d') . ".json");
$data = json_decode($deals_json, true);

$colors_json =  file_get_contents("brandcolors.json");
$brandcolors = json_decode($colors_json, true);

#if (!isset($_GET["action"])) {
#  die("GET parameter 'action' missing.");
#}
#
#$action = $_GET["action"];


$htmloutput = '';

header("Content-Type: application/json");
echo (json_encode($data));
