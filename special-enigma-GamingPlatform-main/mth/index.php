<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Game</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <div class="main">

        <div class="container">
            
            <div class="landing-page">
                <img src="https://img.icons8.com/?size=512&id=44705&format=png" alt="">
                
                <h1>Math Game</h1>

                <button onclick="startGame()">Start Game</button>
                <button onclick="viewLeaderboard()">View Leaderboard</button>
            </div>

            <div class="game-container" style="display: none;">
                <div class="header">
                    <img src="https://img.icons8.com/?size=512&id=44705&format=png" alt="">
                    <h3>Math Game with Leaderboard</h3>
                </div>
                <div class="time-score">
                    <h3 id="timer">Time: 00:30</h3>
                    <h3 id="score">Score: 0</h3>
                </div>

                <h1 id="equation">0 + 0 = ?</h1>
                <input id="answer" type="text" readonly>

                <div class="number-buttons">
                    <button>1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>4</button>
                    <button>5</button>
                    <button>6</button>
                    <button>7</button>
                    <button>8</button>
                    <button>9</button>
                    <button>0</button>
                    <button>C</button>
                    <button id="submit">=</button>
                </div>

                <!-- <button id="submit">Submit</button> -->

                <div class="footer">
                    <button onclick="back()">Back</button>
                    <button id="restart">Restart</button>
                </div>
            </div>

            <div class="leaderboard-container" style="display: none;">
                <div class="header">
                    <img src="https://img.icons8.com/?size=512&id=44705&format=png" alt="">
                    <h3>Math Game with Leaderboard</h3>
                </div>

                <h1>Leaderboards</h1>
                
                <div class="leaderboard">
                    <div class="leaderboard-header">
                        <h4>Rank</h4>
                        <h4>Name</h4>
                        <h4>Score</h4>
                    </div>

                    <?php 
                        include ('./conn/conn.php');
                        
                        $stmt = $conn->prepare("SELECT * FROM tbl_leaderboard ORDER BY score DESC");
                        $stmt->execute();

                        $result = $stmt->fetchAll();

                        $rank = 0;

                        foreach ($result as $row) {
                            $name = $row['name'];
                            $score = $row['score'];
                            $rank ++;
                            ?>
                            
                            <div class="player">
                                <h3><?= $rank ?></h3>
                                <h4><?= $name ?></h4>
                                <h3><?= $score ?></h2>
                            </div>

                            <?php
                        }
                    ?>

                </div>
                <div class="footer">
                    <button onclick="back()">Back</button>
                </div>
            </div>

        </div>

        <!-- Submit Answer Modal -->
        <div class="modal fade" id="submitScoreModal" tabindex="-1" aria-labelledby="submitScore" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitScore">Submit Score</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/submit-answer.php" method="POST">
                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="userScore">Score:</label>
                            <input type="text" class="form-control" id="userScore" name="score" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary form-control">Submit</button>
                    </form>
                </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="./assets/script.js"></script>
</body>
</html>