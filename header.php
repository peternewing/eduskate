<!-- header.php -->
<header class="bg-primary text-white text-center py-3">
    <h1>My Educational Website</h1>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="quiz.php" class="nav-link">Quiz Creator</a></li>
                    <li class="nav-item"><a href="quiz_list.php" class="nav-link">Quiz Viewer</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                        <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
                        <li class="nav-item"><a href="timetable.php" class="nav-link">Timetable</a></li>
                        <li class="nav-item"><a href="homework.php" class="nav-link">Homework</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
