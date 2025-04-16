<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game with Leaderboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        #game-container {
            position: relative;
        }
        
        #game-canvas {
            border: 2px solid #2c3e50;
            background-color: #000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        #score-display {
            font-size: 24px;
            margin: 15px 0;
            color: #2c3e50;
        }
        
        #game-over {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            display: none;
        }
        
        #name-input {
            margin: 20px 0;
            padding: 10px;
            font-size: 16px;
            width: 200px;
            border: none;
            border-radius: 5px;
        }
        
        #submit-score {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        #submit-score:hover {
            background-color: #2ecc71;
        }
        
        #start-button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        #start-button:hover {
            background-color: #2ecc71;
        }
        
        .instructions {
            margin-top: 20px;
            color: #7f8c8d;
            text-align: center;
        }
        
        #leaderboard {
            margin-top: 20px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: white;
        }
        
        #leaderboard h2 {
            text-align: center;
            color: #2c3e50;
            margin-top: 0;
        }
        
        #scores-list {
            list-style-type: none;
            padding: 0;
        }
        
        #scores-list li {
            padding: 8px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        
        #scores-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <h1>Snake 🕹️</h1>
    <div id="game-container">
        <canvas id="game-canvas" width="400" height="400"></canvas>
        <div id="game-over">
            Game Over!
            <div id="final-score" style="font-size: 24px; margin: 20px 0;"></div>
            <input type="text" id="name-input" placeholder="Enter your name" maxlength="15">
            <button id="submit-score">Submit Score</button>
        </div>
    </div>
    <div id="score-display">Score: 0</div>
    <button id="start-button">Start Game</button>
    
    <div id="leaderboard">
        <h2>Top Scores</h2>
        <ul id="scores-list">
            <!-- Scores will be added here dynamically -->
        </ul>
    </div>
    
    <div class="instructions">
        <p>📌 Use arrow keys to control the snake</p>
        <p>📌 Eat the food (red) to grow and increase your score</p>
    </div>

    <script>
        // Game elements
        const canvas = document.getElementById('game-canvas');
        const ctx = canvas.getContext('2d');
        const scoreDisplay = document.getElementById('score-display');
        const startButton = document.getElementById('start-button');
        const gameOverScreen = document.getElementById('game-over');
        const finalScoreDisplay = document.getElementById('final-score');
        const nameInput = document.getElementById('name-input');
        const submitButton = document.getElementById('submit-score');
        const scoresList = document.getElementById('scores-list');
        
        // Game settings
        const gridSize = 20;
        const gridWidth = canvas.width / gridSize;
        const gridHeight = canvas.height / gridSize;
        
        // Game variables
        let snake = [];
        let food = {};
        let direction = 'right';
        let nextDirection = 'right';
        let score = 0;
        let gameSpeed = 150; // milliseconds
        let gameInterval;
        let gameRunning = false;
        let leaderboard = JSON.parse(localStorage.getItem('snakeLeaderboard')) || [];

        // Initialize game
        function initGame() {
            // Create initial snake (3 segments)
            snake = [
                {x: 9 * gridSize, y: 10 * gridSize},
                {x: 8 * gridSize, y: 10 * gridSize},
                {x: 7 * gridSize, y: 10 * gridSize}
            ];
            
            // Generate first food
            generateFood();
            
            // Reset direction and score
            direction = 'right';
            nextDirection = 'right';
            score = 0;
            scoreDisplay.textContent = `Score: ${score}`;
            gameSpeed = 150;
            
            // Hide game over screen
            gameOverScreen.style.display = 'none';
            startButton.style.display = 'block';
        }

        // Generate food at random position
        function generateFood() {
            // Create new food at random position
            food = {
                x: Math.floor(Math.random() * gridWidth) * gridSize,
                y: Math.floor(Math.random() * gridHeight) * gridSize
            };
            
            // Make sure food doesn't appear on snake
            for (let segment of snake) {
                if (segment.x === food.x && segment.y === food.y) {
                    return generateFood();
                }
            }
        }

        // Main game loop
        function gameLoop() {
            if (!gameRunning) return;
            
            // Update direction
            direction = nextDirection;
            
            // Get head position
            const head = {x: snake[0].x, y: snake[0].y};
            
            // Move head based on direction
            switch (direction) {
                case 'up':
                    head.y -= gridSize;
                    break;
                case 'down':
                    head.y += gridSize;
                    break;
                case 'left':
                    head.x -= gridSize;
                    break;
                case 'right':
                    head.x += gridSize;
                    break;
            }
            
            // Check for wall collisions
            if (head.x < 0 || head.x >= canvas.width || head.y < 0 || head.y >= canvas.height) {
                gameOver();
                return;
            }
            
            // Check for self collisions
            for (let i = 0; i < snake.length; i++) {
                if (head.x === snake[i].x && head.y === snake[i].y) {
                    gameOver();
                    return;
                }
            }
            
            // Add new head
            snake.unshift(head);
            
            // Check if food eaten
            if (head.x === food.x && head.y === food.y) {
                // Increase score
                score++;
                scoreDisplay.textContent = `Score: ${score}`;
                
                // Increase speed slightly every 5 points
                if (score % 5 === 0 && gameSpeed > 50) {
                    gameSpeed -= 10;
                    clearInterval(gameInterval);
                    gameInterval = setInterval(gameLoop, gameSpeed);
                }
                
                // Generate new food
                generateFood();
            } else {
                // Remove tail if no food eaten
                snake.pop();
            }
            
            // Draw everything
            draw();
        }

        // Draw game elements
        function draw() {
            // Clear canvas
            ctx.fillStyle = 'black';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Draw snake
            snake.forEach((segment, index) => {
                // Head is darker green
                if (index === 0) {
                    ctx.fillStyle = '#16a085';
                } 
                // Body is lighter green
                else {
                    ctx.fillStyle = '#1abc9c';
                }
                ctx.fillRect(segment.x, segment.y, gridSize, gridSize);
                
                // Add border to segments
                ctx.strokeStyle = '#000';
                ctx.strokeRect(segment.x, segment.y, gridSize, gridSize);
            });
            
            // Draw food (red)
            ctx.fillStyle = '#e74c3c';
            ctx.beginPath();
            ctx.arc(
                food.x + gridSize/2, 
                food.y + gridSize/2, 
                gridSize/2, 
                0, 
                Math.PI * 2
            );
            ctx.fill();
        }

        // Game over function
        function gameOver() {
            gameRunning = false;
            clearInterval(gameInterval);
            
            // Show game over screen with score submission
            finalScoreDisplay.textContent = `Your Score: ${score}`;
            gameOverScreen.style.display = 'flex';
            nameInput.value = '';
            nameInput.focus();
        }

        // Update leaderboard display
        function updateLeaderboard() {
            // Sort leaderboard by score (descending)
            leaderboard.sort((a, b) => b.score - a.score);
            
            // Keep only top 10 scores
            if (leaderboard.length > 10) {
                leaderboard = leaderboard.slice(0, 10);
            }
            
            // Save to localStorage
            localStorage.setItem('snakeLeaderboard', JSON.stringify(leaderboard));
            
            // Update display
            scoresList.innerHTML = '';
            leaderboard.forEach((entry, index) => {
                const li = document.createElement('li');
                li.innerHTML = `<span>${index + 1}. ${entry.name}</span><span>${entry.score}</span>`;
                scoresList.appendChild(li);
            });
        }

        // Start game function
        function startGame() {
            initGame();
            gameRunning = true;
            startButton.style.display = 'none';
            gameInterval = setInterval(gameLoop, gameSpeed);
            draw();
        }

        // Event listeners
        startButton.addEventListener('click', startGame);
        
        submitButton.addEventListener('click', () => {
            const playerName = nameInput.value.trim();
            if (playerName === '') {
                alert('Please enter your name');
                return;
            }
            
            // Add to leaderboard
            leaderboard.push({
                name: playerName,
                score: score,
                date: new Date().toLocaleDateString()
            });
            
            // Update leaderboard
            updateLeaderboard();
            
            // Hide game over screen and show start button
            gameOverScreen.style.display = 'none';
            startButton.style.display = 'block';
        });
        
        // Keyboard controls
        document.addEventListener('keydown', (e) => {
            if (!gameRunning) return;
            
            // Prevent opposite direction movement
            switch (e.key) {
                case 'ArrowUp':
                    if (direction !== 'down') nextDirection = 'up';
                    break;
                case 'ArrowDown':
                    if (direction !== 'up') nextDirection = 'down';
                    break;
                case 'ArrowLeft':
                    if (direction !== 'right') nextDirection = 'left';
                    break;
                case 'ArrowRight':
                    if (direction !== 'left') nextDirection = 'right';
                    break;
            }
        });

        // Initial setup
        updateLeaderboard();
        draw();
    </script>
</body>
</html>