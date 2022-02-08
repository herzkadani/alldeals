<?php
header("Content-Type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: *');
$name = shell_exec("python3 api.py");
echo $name;
?>
