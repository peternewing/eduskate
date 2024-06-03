<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    echo "<p>Please <a href='login.php'>log in</a> to view your quizzes.</p>";
    exit();
}

// Fetch the list of quizzes for the logged-in user
$user_id = $_SESSION['user_id'];
$quizzesQuery = "SELECT * FROM quizzes WHERE user_id='$user_id'";
$quizzesResult = $link->query($quizzesQuery);

$selected_quiz_id = isset($_POST['quiz_id']) ? $link->real_escape_string($_POST['quiz_id']) : '';

// Fetch the questions for the selected quiz
$query = "SELECT * FROM quiz_questions WHERE quiz_id='$selected_quiz_id'";
$result = $link->query($query);

if (!$result) {
    die("Error: " . $link->error);
}

$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Viewer</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>Quiz Viewer</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php">Quiz Creator</a></li>
                <li><a href="quiz_list.php" class="active">Quiz Viewer</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form method="post" action="quiz_list.php">
            <div>
                <label>Select Quiz:</label>
                <select name="quiz_id" onchange="this.form.submit()">
                    <option value="">Select a quiz</option>
                    <?php while ($quiz = $quizzesResult->fetch_assoc()): ?>
                        <option value="<?php echo $quiz['id']; ?>" <?php if ($quiz['id'] == $selected_quiz_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($quiz['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <?php if ($selected_quiz_id && count($questions) > 0): ?>
            <form method="post" action="quiz_list.php">
                <input type="hidden" name="quiz_id" value="<?php echo $selected_quiz_id; ?>">
                <?php foreach ($questions as $question): ?>
                    <div>
                        <h3>Question: <?php echo htmlspecialchars($question["question"]); ?></h3>
                        <input type="radio" name="question_<?php echo $question['id']; ?>" value="1"> <?php echo htmlspecialchars($question["answer1"]); ?><br>
                        <input type="radio" name="question_<?php echo $question['id']; ?>" value="2"> <?php echo htmlspecialchars($question["answer2"]); ?><br>
                        <input type="radio" name="question_<?php echo $question['id']; ?>" value="3"> <?php echo htmlspecialchars($question["answer3"]); ?><br>
                        <input type="radio" name="question_<?php echo $question['id']; ?>" value="4"> <?php echo htmlspecialchars($question["answer4"]); ?><br>
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="submit_quiz">Submit Answers</button>
            </form>
        <?php elseif ($selected_quiz_id): ?>
            <p>No questions available for this quiz.</p>
        <?php endif; ?>

        <?php
        if (isset($_POST['submit_quiz'])) {
            include 'database.php';

            $selected_quiz_id = $link->real_escape_string($_POST['quiz_id']);
            $query = "SELECT * FROM quiz_questions WHERE quiz_id='$selected_quiz_id'";
            $result = $link->query($query);

            $score = 0;
            $total_questions = $result->num_rows;

            echo "<h2>Quiz Results</h2>";
            echo "<ul>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $question_id = $row['id'];
                    $user_answer = isset($_POST['question_' . $question_id]) ? $_POST['question_' . $question_id] : null;
                    $correct_answer = $row['correct_answer'];
                    
                    if ($user_answer == $correct_answer) {
                        $score++;
                        $result_text = "Correct";
                    } else {
                        $result_text = "Incorrect";
                    }

                    echo "<li>";
                    echo "<p>Question: " . htmlspecialchars($row["question"]) . "</p>";
                    echo "<p>Your answer: " . ($user_answer ? htmlspecialchars($row["answer" . $user_answer]) : "No answer") . " - " . $result_text . "</p>";
                    echo "<p>Correct answer: " . htmlspecialchars($row["answer" . $correct_answer]) . "</p>";
                    echo "</li>";
                }
            }

            echo "</ul>";
            echo "<h3>Your score: $score / $total_questions</h3>";
            $link->close();
        }
        ?>

        <?php if (isset($_POST['submit_quiz'])): ?>
            <form method="post" action="quiz_list.php">
                <input type="hidden" name="quiz_id" value="<?php echo $selected_quiz_id; ?>">
                <button type="submit">Reset Answers</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
