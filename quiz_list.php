<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$quizzesQuery = "SELECT * FROM quizzes WHERE user_id='$user_id'";
$quizzesResult = $conn->query($quizzesQuery);

$selected_quiz_id = isset($_POST['quiz_id']) ? $conn->real_escape_string($_POST['quiz_id']) : '';

$query = "SELECT * FROM quiz_questions WHERE quiz_id='$selected_quiz_id'";
$result = $conn->query($query);

if (!$result) {
    die("Error: " . $conn->error);
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <form method="post" action="quiz_list.php">
            <div class="form-group">
                <label>Select Quiz:</label>
                <select name="quiz_id" class="form-control" onchange="this.form.submit()">
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
                    <div class="form-group">
                        <h3>Question: <?php echo htmlspecialchars($question["question"]); ?></h3>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" value="1">
                            <label class="form-check-label"><?php echo htmlspecialchars($question["answer1"]); ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" value="2">
                            <label class="form-check-label"><?php echo htmlspecialchars($question["answer2"]); ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" value="3">
                            <label class="form-check-label"><?php echo htmlspecialchars($question["answer3"]); ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" value="4">
                            <label class="form-check-label"><?php echo htmlspecialchars($question["answer4"]); ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="submit_quiz" class="btn btn-primary">Submit Answers</button>
            </form>
        <?php elseif ($selected_quiz_id): ?>
            <p>No questions available for this quiz.</p>
        <?php endif; ?>

        <?php
        if (isset($_POST['submit_quiz'])) {
            include 'database.php';

            $selected_quiz_id = $conn->real_escape_string($_POST['quiz_id']);
            $query = "SELECT * FROM quiz_questions WHERE quiz_id='$selected_quiz_id'";
            $result = $conn->query($query);

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
            $conn->close();
        }
        ?>

        <?php if (isset($_POST['submit_quiz'])): ?>
            <form method="post" action="quiz_list.php">
                <input type="hidden" name="quiz_id" value="<?php echo $selected_quiz_id; ?>">
                <button type="submit" class="btn btn-secondary">Reset Answers</button>
            </form>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        const highlightType = localStorage.getItem('highlightType');
        const highlightId = localStorage.getItem('highlightId');

        if (highlightType === 'quiz' && highlightId) {
            $(`tr[data-entry-id="${highlightId}"]`).css('background-color', 'yellow');
            localStorage.removeItem('highlightType');
            localStorage.removeItem('highlightId');
        }
    </script>
</body>
</html>
