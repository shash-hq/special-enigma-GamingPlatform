function startGame() {
    document.querySelector('.landing-page').style.display = "none";
    document.querySelector('.game-container').style.display = "";

    const equationEl = document.getElementById('equation');
    const answerEl = document.getElementById('answer');
    const scoreEl = document.getElementById('score');
    const timerEl = document.getElementById('timer');
    const submitEl = document.getElementById('submit');
    const restartEl = document.getElementById('restart');

    let score = 0;
    let timer = 15;
    let intervalId;

    function updateEquation() {
        const operand1 = Math.floor(Math.random() * 10) + 1;
        const operand2 = Math.floor(Math.random() * 10) + 1;
        let operator;
        if (operand1 > operand2) {
            operator = ['*', '+', '-'][Math.floor(Math.random() * 3)];
        } else {
            operator = ['*', '+'][Math.floor(Math.random() * 2)];
        }

        equationEl.textContent = `${operand1} ${operator} ${operand2} = ?`;
    }

    function checkAnswer() {
        const equationText = equationEl.textContent;
        const [operand1, op, operand2] = equationText.split(' ');
        const correctAnswer = calculateAnswer(parseInt(operand1), op, parseInt(operand2));
        const userAnswer = parseInt(answerEl.value);

        if (!isNaN(userAnswer) && userAnswer === correctAnswer) {
            score += 1;
            updateEquation();
            answerEl.value = '';
        } else {
            updateEquation();
            answerEl.value = '';
        }

        scoreEl.textContent = `Score: ${score}`
    }

    // Function to calculate the answer based on the operator
    function calculateAnswer(operand1, operator, operand2) {
        switch (operator) {
            case '+':
                return operand1 + operand2;
            case '-':
                return operand1 - operand2;
            case '*':
                return operand1 * operand2;
            default:
                return NaN;
        }
    }

    // Function to handle button clicks
    function handleButtonClick(value) {
        if (value === 'C') {
            answerEl.value = '';
        } else {
            answerEl.value += value;
        }
    }

    function updateTimer() {
        if (timer > 0) {
            timer--;
            timerEl.textContent = `Time: ${String(Math.floor(timer / 60)).padStart(2, '0')}:${String(timer % 60).padStart(2, '0')}`;
        } else {
            $('#submitScoreModal').modal('show');

            $('#userScore').val(score);
        }
    }

    restartEl.addEventListener('click', () => {
        score = 0;
        timer = 15;
        updateEquation();
        answerEl.value = '';
        intervalId = setInterval(updateTimer, 1000);
    });

    document.querySelectorAll('.number-buttons button').forEach(button => {
        button.addEventListener('click', () => handleButtonClick(button.textContent));
    });

    submitEl.addEventListener('click', checkAnswer);

    updateEquation();
    intervalId = setInterval(updateTimer, 1000);
}

function viewLeaderboard() {
    document.querySelector('.landing-page').style.display = "none";
    document.querySelector('.leaderboard-container').style.display = "";
}

function back() {
    location.reload();
}