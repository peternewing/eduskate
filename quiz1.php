<!DOCTYPE html>
<html>
<head>
    <title>FAQ</title>
</head>
<body>
    <h1>Quiz</h1>

    <!-- Include the PHP code that shows the questions and answers -->
    <?php
    // Include the database connection file
    include 'database.php';

    // Get the questions and answers from the database
    $sql = "SELECT question, answer1, answer2, answer3, answer4, correct_answer FROM quiz_questions";
    $result = $conn->query($sql);

    // Initialize the score to 0
    $score = 0;

    // Process the form data when the form is submitted
    if (isset($_POST['submit'])) {
        foreach ($result as $row) {
            $question = $row['question'];
            if (isset($_POST[$question])) {
                $user_answer = $_POST[$question];
                $correct_answer = $row['correct_answer'];
                if ($user_answer == $correct_answer) {
                    $score++;
                }
            }
        }
        echo '<p>Your score is: ' . $score . '</p>';
    }

    // Display the questions and answers in a form
    echo '<form method="post">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<p>' . $row["question"] . '</p>';
            echo '<input type="radio" name="' . $row["question"] . '" value="' . $row["answer1"] . '">' . $row["answer1"] . '<br>';
            echo '<input type="radio" name="' . $row["question"] . '" value="' . $row["answer2"] . '">' . $row["answer2"] . '<br>';
            echo '<input type="radio" name="' . $row["question"] . '" value="' . $row["answer3"] . '">' . $row["answer3"] . '<br>';
            echo '<input type="radio" name="' . $row["question"] . '" value="' . $row["answer4"] . '">' . $row["answer4"] . '<br>';
        }
    }
    echo '<button type="submit" name="submit">Submit</button>';
    echo '</form>';

    // Close database connection
    $conn->close();
    ?>

</body>
</html>
