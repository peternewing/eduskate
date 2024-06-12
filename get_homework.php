<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, subject, description, due_date FROM homework WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$homework = $result->fetch_assoc();

echo json_encode($homework);
?>
