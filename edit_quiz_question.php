<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $conn->real_escape_string($_POST['question']);
    $answer1 = $conn->real_escape_string($_POST['answer1']);
    $answer2 = $conn->real_escape_string($_POST['answer2']);
    $answer3 = $conn->real_escape_string($_POST['answer3']);
    $answer4 = $conn->real_escape_string($_POST['answer4']);
    $correct_answer = $conn->real_escape_string($_POST['correct_answer']);

    $stmt = $conn->prepare("UPDATE quiz_questions SET question=?, answer1=?, answer2=?, answer3=?, answer4=?, correct_answer=? WHERE id=?");
    $stmt->bind_param("ssssssi", $question, $answer1, $answer2, $answer3, $answer4, $correct_answer, $id);

    if ($stmt->execute()) {
        header("Location: quiz_list.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $stmt = $conn->prepare("SELECT question, answer1, answer2, answer3, answer4, correct_answer FROM quiz_questions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($question, $answer1, $answer2, $answer3, $answer4, $correct_answer);
    $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Quiz Question</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>Edit Quiz Question</h2>
        <form method="POST" action="edit_quiz_question.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label>Question:</label>
                <input type="text" name="question" class="form-control" value="<?php echo htmlspecialchars($question); ?>" required>
            </div>
            <div class="form-group">
                <label>Answer 1:</label>
                <input type="text" name="answer1" class="form-control" value="<?php echo htmlspecialchars($answer1); ?>" required>
            </div>
            <div class="form-group">
                <label>Answer 2:</label>
                <input type="text" name="answer2" class="form-control" value="<?php echo htmlspecialchars($answer2); ?>" required>
            </div>
            <div class="form-group">
                <label>Answer 3:</label>
                <input type="text" name="answer3" class="form-control" value="<?php echo htmlspecialchars($answer3); ?>" required>
            </div>
            <div class="form-group">
                <label>Answer 4:</label>
                <input type="text" name="answer4" class="form-control" value="<?php echo htmlspecialchars($answer4); ?>" required>
            </div>
            <div class="form-group">
                <label>Correct Answer:</label>
                <select name="correct_answer" class="form-control" required>
                    <option value="1" <?php if ($correct_answer == 1) echo 'selected'; ?>>Answer 1</option>
                    <option value="2" <?php if ($correct_answer == 2) echo 'selected'; ?>>Answer 2</option>
                    <option value="3" <?php if ($correct_answer == 3) echo 'selected'; ?>>Answer 3</option>
                    <option value="4" <?php if ($correct_answer == 4) echo 'selected'; ?>>Answer 4</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
