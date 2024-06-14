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

$stmt = $conn->prepare("SELECT id, day, period, subject, room, teacher, week FROM timetable WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$timetableResult = $stmt->get_result();

$highlightId = isset($_GET['highlight_id']) ? $_GET['highlight_id'] : null;
$highlightWeek = isset($_GET['highlight_week']) ? $_GET['highlight_week'] : 'A';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interactive Timetable</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container my-4">
        <h2>Interactive Timetable</h2>
        <div class="btn-group mb-3" role="group">
            <button type="button" class="btn btn-primary" onclick="loadWeek('A')">Week A</button>
            <button type="button" class="btn btn-secondary" onclick="loadWeek('B')">Week B</button>
        </div>
        <button type="button" class="btn btn-success mb-3" onclick="showAddEntryModal()">Add Entry</button>
        <table class="table table-bordered" id="timetable">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                </tr>
            </thead>
            <tbody>
                <!-- Timetable cells will be populated here -->
            </tbody>
        </table>
    </main>

    <!-- Modal for editing timetable entries -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Timetable Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="entryId">
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject">
                        </div>
                        <div class="form-group">
                            <label for="room">Room</label>
                            <input type="text" class="form-control" id="room">
                        </div>
                        <div class="form-group">
                            <label for="teacher">Teacher</label>
                            <input type="text" class="form-control" id="teacher">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-danger" onclick="deleteEntry()">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding timetable entries -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Timetable Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="addSubject">Subject</label>
                            <input type="text" class="form-control" id="addSubject" required>
                        </div>
                        <div class="form-group">
                            <label for="addRoom">Room</label>
                            <input type="text" class="form-control" id="addRoom" required>
                        </div>
                        <div class="form-group">
                            <label for="addTeacher">Teacher</label>
                            <input type="text" class="form-control" id="addTeacher" required>
                        </div>
                        <div class="form-group">
                            <label for="addDay">Day</label>
                            <select class="form-control" id="addDay" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addPeriod">Period</label>
                            <input type="number" class="form-control" id="addPeriod" min="1" max="6" required>
                        </div>
                        <div class="form-group">
                            <label for="addWeek">Week</label>
                            <select class="form-control" id="addWeek" required>
                                <option value="A">Week A</option>
                                <option value="B">Week B</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="addForm">Add Entry</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        let currentWeek = '<?php echo $highlightWeek; ?>';

        function loadWeek(week) {
            currentWeek = week;
            $.ajax({
                url: 'load_timetable.php',
                method: 'GET',
                data: { week: week },
                success: function(data) {
                    const timetable = JSON.parse(data);
                    renderTimetable(timetable);
                }
            });
        }

        function renderTimetable(timetable) {
            const tbody = $('#timetable tbody');
            tbody.empty();

            for (let period = 1; period <= 6; period++) {
                const row = $('<tr></tr>');
                row.append(`<td>${period}</td>`);
                ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'].forEach(day => {
                    const entry = timetable.find(item => item.day === day && item.period == period);
                    const cell = $('<td></td>').data('entry', entry).data('day', day).data('period', period);
                    if (entry) {
                        cell.text(`${entry.subject}\n${entry.room}\n${entry.teacher}`);
                        if (entry.id == '<?php echo $highlightId; ?>') {
                            cell.css('background-color', 'yellow');
                        }
                    }
                    cell.on('click', () => editEntry(entry, day, period));
                    row.append(cell);
                });
                tbody.append(row);
            }
        }

        function editEntry(entry, day, period) {
            $('#entryId').val(entry ? entry.id : '');
            $('#subject').val(entry ? entry.subject : '');
            $('#room').val(entry ? entry.room : '');
            $('#teacher').val(entry ? entry.teacher : '');
            $('#editModal').data('day', day).data('period', period).modal('show');
        }

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#entryId').val();
            const subject = $('#subject').val();
            const room = $('#room').val();
            const teacher = $('#teacher').val();
            const day = $('#editModal').data('day');
            const period = $('#editModal').data('period');

            $.ajax({
                url: 'save_timetable.php',
                method: 'POST',
                data: { id, subject, room, teacher, day, period, week: currentWeek },
                success: function() {
                    $('#editModal').modal('hide');
                    loadWeek(currentWeek);
                }
            });
        });

        function showAddEntryModal() {
            $('#addModal').modal('show');
        }

        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            const subject = $('#addSubject').val();
            const room = $('#addRoom').val();
            const teacher = $('#addTeacher').val();
            const day = $('#addDay').val();
            const period = $('#addPeriod').val();
            const week = $('#addWeek').val();

            $.ajax({
                url: 'save_timetable.php',
                method: 'POST',
                data: { subject, room, teacher, day, period, week },
                success: function() {
                    $('#addModal').modal('hide');
                    loadWeek(currentWeek);
                }
            });
        });

        function deleteEntry() {
            const id = $('#entryId').val();
            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: 'delete_timetable.php',
                    method: 'POST',
                    data: { id },
                    success: function() {
                        $('#editModal').modal('hide');
                        loadWeek(currentWeek);
                    }
                });
            }
        }

        $(document).ready(function() {
            loadWeek(currentWeek);
        });
    </script>
</body>
</html>
