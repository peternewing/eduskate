<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, subject, description, due_date FROM homework WHERE user_id = ?");
if (!$stmt) {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    die("Database error. Please try again later.");
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    die("Database error. Please try again later.");
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Homework</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>Your Homework</h2>
        <button type="button" class="btn btn-success mb-3" onclick="showAddHomeworkModal()">Add Homework</button>
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
                            <button class="btn btn-warning" onclick="showEditHomeworkModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['subject'])); ?>', '<?php echo htmlspecialchars(addslashes($row['description'])); ?>', '<?php echo $row['due_date']; ?>')">Edit</button>
                            <a href="delete_homework.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal for adding homework -->
    <div class="modal fade" id="addHomeworkModal" tabindex="-1" role="dialog" aria-labelledby="addHomeworkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHomeworkModalLabel">Add Homework</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addHomeworkForm">
                        <div class="form-group">
                            <label for="addSubject">Subject</label>
                            <input type="text" class="form-control" id="addSubject" required>
                        </div>
                        <div class="form-group">
                            <label for="addDescription">Description</label>
                            <textarea class="form-control" id="addDescription" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="addDueDate">Due Date</label>
                            <input type="date" class="form-control" id="addDueDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="addHomeworkForm">Add Homework</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing homework -->
    <div class="modal fade" id="editHomeworkModal" tabindex="-1" role="dialog" aria-labelledby="editHomeworkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHomeworkModalLabel">Edit Homework</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editHomeworkForm">
                        <input type="hidden" id="editHomeworkId">
                        <div class="form-group">
                            <label for="editSubject">Subject</label>
                            <input type="text" class="form-control" id="editSubject" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <textarea class="form-control" id="editDescription" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editDueDate">Due Date</label>
                            <input type="date" class="form-control" id="editDueDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="editHomeworkForm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function showAddHomeworkModal() {
            $('#addHomeworkModal').modal('show');
        }

        $('#addHomeworkForm').on('submit', function(e) {
            e.preventDefault();
            const subject = $('#addSubject').val();
            const description = $('#addDescription').val();
            const due_date = $('#addDueDate').val();

            $.ajax({
                url: 'add_homework.php',
                method: 'POST',
                data: { subject, description, due_date },
                success: function() {
                    $('#addHomeworkModal').modal('hide');
                    location.reload();
                }
            });
        });

        function showEditHomeworkModal(id, subject, description, due_date) {
            $('#editHomeworkId').val(id);
            $('#editSubject').val(subject);
            $('#editDescription').val(description);
            $('#editDueDate').val(due_date);
            $('#editHomeworkModal').modal('show');
        }

        $('#editHomeworkForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#editHomeworkId').val();
            const subject = $('#editSubject').val();
            const description = $('#editDescription').val();
            const due_date = $('#editDueDate').val();

            $.ajax({
                url: 'edit_homework.php',
                method: 'POST',
                data: { id, subject, description, due_date },
                success: function() {
                    $('#editHomeworkModal').modal('hide');
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
