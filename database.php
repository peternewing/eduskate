<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host_name = 'db5015855648.hosting-data.io';
$database = 'dbs12926414';
$user_name = 'dbu5480211';
$password = 'B@rripper1998';

$link = new mysqli($host_name, $user_name, $password, $database);

if ($link->connect_error) {
    die('<p>Failed to connect to MySQL: ' . $link->connect_error . '</p>');
}

$link->set_charset("utf8");
?>
