<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$week = $_GET['week'];

$stmt = $conn->prepare("SELECT * FROM timetable WHERE user_id = ? AND week = ?");
$stmt->bind_param('is', $user_id, $week);
$stmt->execute();
$result = $stmt->get_result();

$timetable = [];
while ($row = $result->fetch_assoc()) {
    $timetable[] = $row;
}

echo json_encode($timetable);
?>
