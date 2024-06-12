<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        <section class="hero text-center mb-5">
            <h2>Learn Something New Today</h2>
            <p>Explore our selection of courses and start learning today!</p>
            <a href="#" class="btn btn-primary">View Courses</a>
        </section>
        <section class="courses">
            <h2>Featured Courses</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Introduction to Programming</h3>
                            <p class="card-text">Learn the basics of programming with this introductory course.</p>
                            <button class="btn btn-primary">Enroll Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Web Development Fundamentals</h3>
                            <p class="card-text">Discover the fundamentals of web development with HTML, CSS, and JavaScript.</p>
                            <button class="btn btn-primary">Enroll Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Data Science Essentials</h3>
                            <p class="card-text">Explore the basics of data science with Python and Pandas.</p>
                            <button class="btn btn-primary">Enroll Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php if (isset($_SESSION['username'])): ?>
        <section class="timetable mt-5">
            <h2>Your Timetable</h2>
            <a href="add_timetable.php" class="btn btn-success mb-3">Add Timetable Entry</a>
            <?php
            require 'database.php';
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("SELECT id, day, start_time, end_time, subject FROM timetable WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['day']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td>
                                <a href="edit_timetable.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_timetable.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
        <section class="homework mt-5">
            <h2>Your Homework</h2>
            <a href="add_homework.php" class="btn btn-success mb-3">Add Homework</a>
            <?php
            $stmt = $conn->prepare("SELECT id, subject, description, due_date FROM homework WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                            <td>
                                <a href="edit_homework.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_homework.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
