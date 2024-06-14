<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'database.php';

if (isset($_POST['register_btn'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $password2 = $conn->real_escape_string($_POST['password2']);
    $type = $conn->real_escape_string($_POST['type']);

    if ($password == $password2) {
        // Validate password complexity
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $symbol = preg_match('@[^\w]@', $password);
        $length = strlen($password) >= 8;

        $complexity = $uppercase + $lowercase + $number + $symbol;

        if ($length && $complexity >= 3) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

            $query = "INSERT INTO users (username, email, password, type) VALUES ('$username', '$email', '$hashed_password', '$type')";

            if ($conn->query($query) === TRUE) {
                $_SESSION['message'] = "You are now registered";
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $query . "<br>" . $conn->error;
            }
        } else {
            echo '<script>alert("Password must be at least 8 characters long and include at least three of the following: a lowercase letter, an uppercase letter, a number, or a symbol.")</script>';
        }
    } else {
        echo '<script>alert("Passwords do not match")</script>';
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>Registration Form</h2>
        <form method="post" action="register.php">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="password2" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Type:</label>
                <select name="type" class="form-control" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            <button type="submit" name="register_btn" class="btn btn-primary">Register</button>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
