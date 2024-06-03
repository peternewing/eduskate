<?php
session_start();
include 'database.php';

if (isset($_POST['login_btn'])) {
    $username = $link->real_escape_string($_POST['username']);
    $password = $link->real_escape_string($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $link->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "Username and Password combination incorrect";
        }
    } else {
        $_SESSION['message'] = "Username and Password combination incorrect";
    }
    $link->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>Login Form</h1>
        <nav>
            <ul class="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php">Quiz Creator</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form method="post" action="login.php">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <button type="submit" name="login_btn">Login</button>
            </div>
            <?php if (isset($_SESSION['message'])): ?>
                <div><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
        </form>
    </main>
</body>
</html>
