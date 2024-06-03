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
