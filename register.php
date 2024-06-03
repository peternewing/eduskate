<?php
session_start();
include 'database.php';

if (isset($_POST['register_btn'])) {
    $username = $link->real_escape_string($_POST['username']);
    $email = $link->real_escape_string($_POST['email']);
    $password = $link->real_escape_string($_POST['password']);
    $password2 = $link->real_escape_string($_POST['password2']);
    $type = $link->real_escape_string($_POST['type']);

    if ($password == $password2) {
        // Validate password strength
        if (strlen($password) >= 10 && preg_match('@[A-Z]@', $password) && preg_match('@[a-z]@', $password) && preg_match('@[0-9]@', $password) && preg_match('@[^\w]@', $password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

            $query = "INSERT INTO users (username, email, password, type) VALUES ('$username', '$email', '$hashed_password', '$type')";

            if ($link->query($query) === TRUE) {
                $_SESSION['message'] = "You are now registered";
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $query . "<br>" . $link->error;
            }
        } else {
            echo '<script>alert("Password requires capital, lowercase, numbers, and symbols and at least 10 characters long")</script>';
        }
    } else {
        echo '<script>alert("Passwords do not match")</script>';
    }
    $link->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>Registration Form</h1>
        <nav>
            <ul class="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php">Quiz Creator</a></li>
                <li><a href="register.php" class="active">Register</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form method="post" action="register.php">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Confirm Password:</label>
                <input type="password" name="password2" required>
            </div>
            <div>
                <label>Type:</label>
                <select name="type" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            <div>
                <button type="submit" name="register_btn">Register</button>
            </div>
        </form>
    </main>
</body>
</html>
