<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$id = $_POST['id'];
$subject = $_POST['subject'];
$room = $_POST['room'];
$teacher = $_POST['teacher'];
$day = $_POST['day'];
$period = $_POST['period'];
$week = $_POST['week'];

if ($id) {
    $stmt = $conn->prepare("UPDATE timetable SET subject = ?, room = ?, teacher = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param('sssii', $subject, $room, $teacher, $id, $user_id);
} else {
    $stmt = $conn->prepare("INSERT INTO timetable (user_id, week, day, period, subject, room, teacher) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ississs', $user_id, $week, $day, $period, $subject, $room, $teacher);
}

$stmt->execute();
?>
