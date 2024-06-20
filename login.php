<?php
session_start();
include 'database.php';

if (isset($_POST['login_btn'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

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
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>Login Form</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
          <a href="forgot_password.php">Forgot your password?</a>

            <div>
                <button type="submit" name="login_btn" class="btn btn-primary">Login</button>
            </div>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger mt-3"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
