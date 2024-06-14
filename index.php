<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'database.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $isLoggedIn ? $_SESSION['user_id'] : null;

// Fetch timetable data if logged in
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT id, day, period, subject, room, teacher, week FROM timetable WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $timetableResult = $stmt->get_result();
}

// Fetch homework data if logged in
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT id, subject, description, due_date FROM homework WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $homeworkResult = $stmt->get_result();
}

// Fetch quiz data if logged in
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT id, name FROM quizzes WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $quizResult = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Educational Website</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <?php if ($isLoggedIn): ?>
            <section class="timetable mb-5">
                <h2>Your Timetable</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Period</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Teacher</th>
                            <th>Week</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $timetableResult->fetch_assoc()): ?>
                            <tr data-entry-id="<?php echo $row['id']; ?>" onclick="highlightTimetableEntry(<?php echo $row['id']; ?>, '<?php echo $row['week']; ?>')">
                                <td><?php echo htmlspecialchars($row['day']); ?></td>
                                <td><?php echo htmlspecialchars($row['period']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['room']); ?></td>
                                <td><?php echo htmlspecialchars($row['teacher']); ?></td>
                                <td><?php echo htmlspecialchars($row['week']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <section class="homework mb-5">
                <h2>Your Homework</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $homeworkResult->fetch_assoc()): ?>
                            <tr data-entry-id="<?php echo $row['id']; ?>" onclick="highlightHomeworkEntry(<?php echo $row['id']; ?>)">
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <section class="quizzes mb-5">
                <h2>Your Quizzes</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Quiz Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $quizResult->fetch_assoc()): ?>
                            <tr data-entry-id="<?php echo $row['id']; ?>" onclick="highlightQuizEntry(<?php echo $row['id']; ?>)">
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        <?php else: ?>
            <section class="hero text-center mb-5">
                <h2>Welcome to My Educational Website</h2>
                <p>Explore our selection of courses, quizzes, and educational resources. Register now to start your learning journey!</p>
                <a href="register.php" class="btn btn-primary">Register</a>
                <a href="login.php" class="btn btn-secondary">Log In</a>
            </section>
            <section class="features">
                <h2>Website Features</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Timetable Management</h3>
                                <p class="card-text">Easily manage your class schedules and stay organized.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Homework Tracking</h3>
                                <p class="card-text">Keep track of your assignments and never miss a due date.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Quiz Creator</h3>
                                <p class="card-text">Create and take quizzes to test your knowledge and improve your skills.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function highlightTimetableEntry(id, week) {
            localStorage.setItem('highlightType', 'timetable');
            localStorage.setItem('highlightId', id);
            localStorage.setItem('highlightWeek', week);
            window.location.href = 'timetable.php?highlight_id=' + id + '&highlight_week=' + week;
        }

        function highlightHomeworkEntry(id) {
            localStorage.setItem('highlightType', 'homework');
            localStorage.setItem('highlightId', id);
            window.location.href = 'homework.php?highlight_id=' + id;
        }

        function highlightQuizEntry(id) {
            localStorage.setItem('highlightType', 'quiz');
            localStorage.setItem('highlightId', id);
            window.location.href = 'quiz_list.php?highlight_id=' + id;
        }
    </script>
</body>
</html>
