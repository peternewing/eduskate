<?php
// Include the database connection file
include 'database.php';

// Get form data
$question = isset($_POST['question']) ? $_POST['question'] : '';
$answer1 = isset($_POST['answer1']) ? $_POST['answer1'] : '';
$answer2 = isset($_POST['answer2']) ? $_POST['answer2'] : '';
$answer3 = isset($_POST['answer3']) ? $_POST['answer3'] : '';
$answer4 = isset($_POST['answer4']) ? $_POST['answer4'] : '';
$correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : '';

// Assign correct_answer to the correct answer value
switch ($correct_answer) {
    case "1":
        $correct_answer = $answer1;
        break;
    case "2":
        $correct_answer = $answer2;
        break;
    case "3":
        $correct_answer = $answer3;
        break;
    case "4":
        $correct_answer = $answer4;
        break;
    default:
        $correct_answer = '';
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO quiz_questions (question, answer1, answer2, answer3, answer4, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $question, $answer1, $answer2, $answer3, $answer4, $correct_answer);

// Execute SQL statement
if ($stmt->execute()) {
    echo "Quiz question uploaded successfully";
} else {
    echo "Error uploading quiz question: " . $stmt->error;
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Quiz Creation Portal</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<meta http-equiv = "refresh" content = "seconds"; url = quiz.php>
	<header>
		<h1>Quiz Creation Portal</h1>
		<nav>
			<ul>
				<li><a href="index.php" class="active">Home</a></li>
				<li><a href="quiz.php" class="active">Quiz</a></li>
                <li><a href="register.php" class="active">Register</a></li>
                <li><a href="logout.php" class="active">Log Out</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="profile.php">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
			</ul>
		</nav>
	</header>
	<main>
		<section>
			<h2>quiz submitted???</h2>

		</section>
		<section>
			<h2>Existing Quizzes</h2>
			<ul>
				<li><a href="quiz1.php">Quiz 1</a></li>
				<li><a href="#">Quiz 2</a></li>
				<li><a href="#">Quiz 3</a></li>
			</ul>
		</section>
	</main>
	<script src="script.js"></script>
</body>
</html>
