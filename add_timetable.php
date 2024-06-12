<?php
require 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $day = trim($_POST['day']);
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);
    $subject = trim($_POST['subject']);

    if (empty($day) || empty($start_time) || empty($end_time) || empty($subject)) {
        echo "All fields are required.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO timetable (user_id, day, start_time, end_time, subject) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $day, $start_time, $end_time, $subject);

    if ($stmt->execute()) {
        header("Location: timetable.php");
    } else {
        error_log("Error: " . $stmt->error);
        echo "An error occurred while adding timetable entry.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Timetable Entry</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add Timetable Entry</h2>
        <form method="POST" action="add_timetable.php">
            <div class="form-group">
                <label for="day">Day</label>
                <input type="text" class="form-control" name="day" id="day" placeholder="Day" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" class="form-control" name="start_time" id="start_time" placeholder="Start Time" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" class="form-control" name="end_time" id="end_time" placeholder="End Time" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Timetable Entry</button>
        </form>
    </div>
</body>
</html>
