// Select the "Add Question" button and the container for questions
const addQuestionButton = document.getElementById('add-question');
const questionContainer = document.getElementById('quiz-questions');

// Track the number of questions added so far
let questionCount = 1;

// Add a new question when the "Add Question" button is clicked
addQuestionButton.addEventListener('click', function () {
    questionCount++;

    // Create a new question container
    const newQuestion = document.createElement('div');
    newQuestion.classList.add('quiz-question');

    // Add the question title input field
    const questionTitleLabel = document.createElement('label');
    questionTitleLabel.setAttribute('for', `question-${questionCount}`);
    questionTitleLabel.innerText = `Question ${questionCount}:`;
    const questionTitleInput = document.createElement('input');
    questionTitleInput.setAttribute('type', 'text');
    questionTitleInput.setAttribute('id', `question-${questionCount}`);
    questionTitleInput.setAttribute('name', `question-${questionCount}`);
    questionTitleInput.setAttribute('required', '');
    newQuestion.appendChild(questionTitleLabel);
    newQuestion.appendChild(questionTitleInput);

    // Add the answer options for the new question
    for (let i = 1; i <= 3; i++) {
        const answerLabel = document.createElement('label');
        answerLabel.setAttribute('for', `answer-${questionCount}-${i}`);
        answerLabel.innerText = `Answer ${i}:`;
        const answerInput = document.createElement('input');
        answerInput.setAttribute('type', 'text');
        answerInput.setAttribute('id', `answer-${questionCount}-${i}`);
        answerInput.setAttribute('name', `answer-${questionCount}-${i}`);
        answerInput.setAttribute('required', '');
        const answerCorrect = document.createElement('input');
        answerCorrect.setAttribute('type', 'radio');
        answerCorrect.setAttribute('id', `correct-${questionCount}-${i}`);
        answerCorrect.setAttribute('name', `correct-${questionCount}`);
        answerCorrect.setAttribute('value', `${i}`);
        answerCorrect.setAttribute('required', '');
        const correctLabel = document.createElement('label');
        correctLabel.setAttribute('for', `correct-${questionCount}-${i}`);
        correctLabel.innerText = 'Correct';
        newQuestion.appendChild(answerLabel);
        newQuestion.appendChild(answerInput);
        newQuestion.appendChild(answerCorrect);
        newQuestion.appendChild(correctLabel);
    }

    // Add the new question to the question container
    questionContainer.appendChild(newQuestion);
});

let currentWeek = 'A';

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
            const entry = timetable.find(item => item.day === day && item.period === period);
            const cell = $('<td></td>').data('entry', entry);
            if (entry) {
                cell.text(`${entry.subject}\n${entry.room}\n${entry.teacher}`);
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

// Initial load
$(document).ready(function() {
    loadWeek('A');
});


