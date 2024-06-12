<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_quiz_btn'])) {
    $quiz_name = $conn->real_escape_string($_POST['quiz_name']);
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO quizzes (name, user_id) VALUES ('$quiz_name', '$user_id')";
    
    if ($conn->query($query) === TRUE) {
        echo "New quiz created successfully";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$user_id = $_SESSION['user_id'];
$quizzesQuery = "SELECT * FROM quizzes WHERE user_id='$user_id'";
$quizzesResult = $conn->query($quizzesQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_question_btn'])) {
    $quiz_id = $conn->real_escape_string($_POST['quiz_id']);
    $question = $conn->real_escape_string($_POST['question']);
    $answer1 = $conn->real_escape_string($_POST['answer1']);
    $answer2 = $conn->real_escape_string($_POST['answer2']);
    $answer3 = $conn->real_escape_string($_POST['answer3']);
    $answer4 = $conn->real_escape_string($_POST['answer4']);
    $correct_answer = $conn->real_escape_string($_POST['correct_answer']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO quiz_questions (quiz_id, user_id, question, answer1, answer2, answer3, answer4, correct_answer) VALUES ('$quiz_id', '$user_id', '$question', '$answer1', '$answer2', '$answer3', '$answer4', '$correct_answer')";

    if ($conn->query($query) === TRUE) {
        echo "New question added successfully";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Creation Portal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <section>
            <h2>Create a New Quiz</h2>
            <form method="post" action="quiz.php">
                <div class="form-group">
                    <label>Quiz Name:</label>
                    <input type="text" name="quiz_name" class="form-control" required>
                </div>
                <div>
                    <button type="submit" name="create_quiz_btn" class="btn btn-primary">Create Quiz</button>
                </div>
            </form>
        </section>
        <section>
            <h2>Add a Question to a Quiz</h2>
            <form method="post" action="quiz.php">
                <div class="form-group">
                    <label>Select Quiz:</label>
                    <select name="quiz_id" class="form-control" required>
                        <?php while ($quiz = $quizzesResult->fetch_assoc()): ?>
                            <option value="<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Question:</label>
                    <input type="text" name="question" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Answer 1:</label>
                    <input type="text" name="answer1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Answer 2:</label>
                    <input type="text" name="answer2" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Answer 3:</label>
                    <input type="text" name="answer3" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Answer 4:</label>
                    <input type="text" name="answer4" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Correct Answer:</label>
                    <select name="correct_answer" class="form-control" required>
                        <option value="1">Answer 1</option>
                        <option value="2">Answer 2</option>
                        <option value="3">Answer 3</option>
                        <option value="4">Answer 4</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="submit_question_btn" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
