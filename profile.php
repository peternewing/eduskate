<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'database.php'; // Ensure this includes the correct connection script

$username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username='$username'";
$result = $link->query($query);

if ($result) {
    $user = $result->fetch_assoc();
} else {
    die("Error: " . $link->error);
}
$link->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" type="text/css" href="style3.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>My Profile</h1>
        <nav>
            <ul class="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php">Quiz Creator</a></li>
                <li><a href="quiz_list.php">Quiz Viewer</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="profile.php" class="active">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Personal Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <!-- Add more user details if needed -->
        </section>
    </main>
</body>
</html>
