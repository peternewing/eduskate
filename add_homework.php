<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("INSERT INTO homework (user_id, subject, description, due_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $subject, $description, $due_date);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
