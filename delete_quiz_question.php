<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM quiz_questions WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: quiz_list.php");
} else {
    echo "Error: " . $stmt->error;
}
?>
