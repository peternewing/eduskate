<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Educational Website</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>My Educational Website</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="quiz.php">Quiz Creator</a></li>
                <li><a href="quiz_list.php">Quiz Viewer</a></li>
                <li><a href="register.php">Register</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php">Log Out</a></li>
                    <li><a href="profile.php">Profile</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Learn Something New Today</h2>
            <p>Explore our selection of courses and start learning today!</p>
            <a href="#" class="cta">View Courses</a>
        </section>
        <section class="courses">
            <h2>Featured Courses</h2>
            <ul>
                <li>
                    <h3>Introduction to Programming</h3>
                    <p>Learn the basics of programming with this introductory course.</p>
                    <button class="btn">Enroll Now</button>
                </li>
                <li>
                    <h3>Web Development Fundamentals</h3>
                    <p>Discover the fundamentals of web development with HTML, CSS, and JavaScript.</p>
                    <button class="btn">Enroll Now</button>
                </li>
                <li>
                    <h3>Data Science Essentials</h3>
                    <p>Explore the basics of data science with Python and Pandas.</p>
                    <button class="btn">Enroll Now</button>
                </li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 My Educational Website</p>
    </footer>
</body>
</html
