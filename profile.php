<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'database.php';

$username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($query);

if ($result) {
    $user = $result->fetch_assoc();
} else {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>My Profile</h2>
        <section>
            <h3>Personal Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <!-- Add more user details if needed -->
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
