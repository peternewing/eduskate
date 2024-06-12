<?php
require 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);

    if (empty($subject) || empty($description) || empty($due_date)) {
        echo "All fields are required.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE homework SET subject=?, description=?, due_date=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssii", $subject, $description, $due_date, $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: homework.php");
    } else {
        error_log("Error: " . $stmt->error);
        echo "An error occurred while updating homework.";
    }
    $stmt->close();
    $conn->close();
} else {
    $stmt = $conn->prepare("SELECT subject, description, due_date FROM homework WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($subject, $description, $due_date);
    $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Homework</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Homework</h2>
        <form method="POST" action="edit_homework.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" class="form-control" name="due_date" id="due_date" value="<?php echo htmlspecialchars($due_date); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Homework</button>
        </form>
    </div>
</body>
</html>
