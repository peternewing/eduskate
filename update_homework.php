<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_POST['id'];
$subject = $_POST['subject'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE homework SET subject = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param('sssii', $subject, $description, $due_date, $id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}
?>
