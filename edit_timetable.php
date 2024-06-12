<?php
require 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day = trim($_POST['day']);
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);
    $subject = trim($_POST['subject']);

    if (empty($day) || empty($start_time) || empty($end_time) || empty($subject)) {
        echo "All fields are required.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE timetable SET day=?, start_time=?, end_time=?, subject=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssii", $day, $start_time, $end_time, $subject, $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: timetable.php");
    } else {
        error_log("Error: " . $stmt->error);
        echo "An error occurred while updating timetable entry.";
    }
    $stmt->close();
    $conn->close();
} else {
    $stmt = $conn->prepare("SELECT day, start_time, end_time, subject FROM timetable WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($day, $start_time, $end_time, $subject);
    $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Timetable Entry</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Timetable Entry</h2>
        <form method="POST" action="edit_timetable.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="day">Day</label>
                <input type="text" class="form-control" name="day" id="day" value="<?php echo htmlspecialchars($day); ?>" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" class="form-control" name="start_time" id="start_time" value="<?php echo htmlspecialchars($start_time); ?>" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" class="form-control" name="end_time" id="end_time" value="<?php echo htmlspecialchars($end_time); ?>" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Timetable Entry</button>
        </form>
    </div>
</body>
</html>
