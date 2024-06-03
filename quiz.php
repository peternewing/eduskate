<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_quiz_btn'])) {
    $quiz_name = $link->real_escape_string($_POST['quiz_name']);
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO quizzes (name, user_id) VALUES ('$quiz_name', '$user_id')";
    
    if ($link->query($query) === TRUE) {
        echo "New quiz created successfully";
    } else {
        echo "Error: " . $query . "<br>" . $link->error;
    }
}

$user_id = $_SESSION['user_id'];
$quizzesQuery = "SELECT * FROM quizzes WHERE user_id='$user_id'";
$quizzesResult = $link->query($quizzesQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_question_btn'])) {
    $quiz_id = $link->real_escape_string($_POST['quiz_id']);
    $question = $link->real_escape_string($_POST['question']);
    $answer1 = $link->real_escape_string($_POST['answer1']);
    $answer2 = $link->real_escape_string($_POST['answer2']);
    $answer3 = $link->real_escape_string($_POST['answer3']);
    $answer4 = $link->real_escape_string($_POST['answer4']);
    $correct_answer = $link->real_escape_string($_POST['correct_answer']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO quiz_questions (quiz_id, user_id, question, answer1, answer2, answer3, answer4, correct_answer) VALUES ('$quiz_id', '$user_id', '$question', '$answer1', '$answer2', '$answer3', '$answer4', '$correct_answer')";

    if ($link->query($query) === TRUE) {
        echo "New question added successfully";
    } else {
        echo "Error: " . $query . "<br>" . $link->error;
    }
    $link->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Creation Portal</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>Quiz Creation Portal</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php" class="active">Quiz Creator</a></li>
                <li><a href="quiz_list.php">Quiz Viewer</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Create a New Quiz</h2>
            <form method="post" action="quiz.php">
                <div>
                    <label>Quiz Name:</label>
                    <input type="text" name="quiz_name" required>
                </div>
                <div>
                    <button type="submit" name="create_quiz_btn">Create Quiz</button>
                </div>
            </form>
        </section>
        <section>
            <h2>Add a Question to a Quiz</h2>
            <form method="post" action="quiz.php">
                <div>
                    <label>Select Quiz:</label>
                    <select name="quiz_id" required>
                        <?php while ($quiz = $quizzesResult->fetch_assoc()): ?>
                            <option value="<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label>Question:</label>
                    <input type="text" name="question" required>
                </div>
                <div>
                    <label>Answer 1:</label>
                    <input type="text" name="answer1" required>
                </div>
                <div>
                    <label>Answer 2:</label>
                    <input type="text" name="answer2" required>
                </div>
                <div>
                    <label>Answer 3:</label>
                    <input type="text" name="answer3" required>
                </div>
                <div>
                    <label>Answer 4:</label>
                    <input type="text" name="answer4" required>
                </div>
                <div>
                    <label>Correct Answer:</label>
                    <select name="correct_answer" required>
                        <option value="1">Answer 1</option>
                        <option value="2">Answer 2</option>
                        <option value="3">Answer 3</option>
                        <option value="4">Answer 4</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="submit_question_btn">Submit</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
