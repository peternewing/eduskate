<?php
require 'config.php';

$stmt = $conn->prepare("SELECT users.email, homework.subject, homework.due_date FROM users JOIN homework ON users.id = homework.user_id WHERE homework.due_date = CURDATE() + INTERVAL 1 DAY");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $to = $row['email'];
    $subject = "Reminder: Homework Due Tomorrow";
    $message = "You have homework due tomorrow for the subject: " . $row['subject'];
    $headers = "From: no-reply@studentplanner.com";

    mail($to, $subject, $message, $headers);
}
?>
