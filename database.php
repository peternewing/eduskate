<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host_name = 'db5015855648.hosting-data.io';
$database = 'dbs12926414';
$user_name = 'dbu5480211';
$password = 'B@rripper1998';

$conn = new mysqli($host_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Failed to connect to MySQL: ' . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
