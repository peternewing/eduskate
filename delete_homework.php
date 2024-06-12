<?php
require 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM homework WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: homework.php");
} else {
    error_log("Error: " . $stmt->error);
    echo "An error occurred while deleting homework.";
}
$stmt->close();
$conn->close();
?>
