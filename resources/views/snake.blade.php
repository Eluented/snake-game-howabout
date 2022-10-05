<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Snake</title>
    <script src="{{url('js/snake.js')}}"></script>
    <link rel="stylesheet" href="{{url('css/app.css')}}">
    <link rel="icon" type="image/x-icon" href="{{url('images/snake.ico')}}">
</head>

<body>

    <div id="bg-modal1">
        <div class="instructions">
            <h2 class="how-to-play">How to Play:</h2>
            <ul>
                <li><strong>W A S D</strong> or <strong>Arrow Keys</strong> to Move</li>
                <li>Hold <strong>Shift</strong> to Run!</li>
                <li>Hold <strong>Space</strong> to Pause/Play</li>
            </ul>
        </div>

        <div class="difficulty-buttons">
            <button id="easy" class="difficulty" onclick="game.easy()">EASY</button>
            <button id="medium" class="difficulty" onclick="game.medium()">MEDIUM</button>
            <button id="hard" class="difficulty" onclick="game.hard()">HARD</button>
        </div>
    </div>

    <div id="bg-modal2">
        <div class="container">
            <h1>
                <span class="icon">🎉</span>
                Flex Your <span class="score">0</span> Score to the Leaderboard!
                <span class="icon">🎉</span>
            </h1>

            <form id="searchForm">
                <label for="username"> Username </label>
                <input id="username" type="text" class="form-control" placeholder="Enter Username"
                    aria-label="Users's username" name="username">
            </form>

            <button type="submit" form="searchForm" value="Submit">Submit</button>
        </div>
    </div>

    <div id="controls">
        <button class="set-difficulty">SET DIFFICULTY</button>

        <div>
            <button class="play-button" onclick="game.play()">PLAY</button>
            <button class="restart-button" onclick="game.restart()">RESTART</button>
            <button class="pause-button" onclick="game.snake.pause()">PAUSE (SPACE)</button>
            <button class="resume-button" onclick="game.snake.move()">RESUME</button>
        </div>

        <button class="score-button">
            SCORE: <span class="score">0</span>
        </button>



        <button class="leaderboard-btn">LEADERBOARD</button>
    </div>

    <div id="board">
        <div id="gameOver">
            <h1 class="game-over-text">Game Over!</h1>
            <h2 class="game-over-text">Your Score is <span class="score">0</span></h2>
            <button id="submit-score-button">SUBMIT SCORE</button>
        </div>
    </div>

    <h4 class="response"></h4>


    <script type="text/javascript">
        const board = document.getElementById('board');
        const controls = document.getElementById('controls');
        const game = new SnakeGame(board, controls);

        // Closing - Setting Difficulty
        let difficultyModal = document.querySelector('#bg-modal1');


        document.querySelectorAll('.difficulty').forEach(el => {
            el.addEventListener('click', () => {
                difficultyModal.style.display = 'none';
            })
        })

        // Opening - Setting Difficulty
        let setDifficultyBtn = document.querySelector('.set-difficulty');

        setDifficultyBtn.addEventListener('click', () => {
            difficultyModal.style.display = 'flex';
        })

        // Leaderboard Button
        let leaderboardBtn = document.querySelector('.leaderboard-btn');

        leaderboardBtn.addEventListener('click', () => {
            window.location.href = 'leaderboard';
        })

        // Submit Score Modal
        let submitScoreModal = document.querySelector('#bg-modal2');
        let submitScoreBtn = document.querySelector('#submit-score-button');

        submitScoreBtn.addEventListener('click', () => {
            submitScoreModal.style.display = 'flex';
        })

        // Leaderboard Submit Form
        let form = document.getElementById("searchForm")

        form.addEventListener("submit", e => {
            e.preventDefault();

            const username = e.target.username.value
            const score = document.querySelector('.score').innerText


            postUsername(username, score);

            submitScoreModal.style.display = 'none';

        })

        // Form POST Funtion
        async function postUsername(username, score) {
            try {
                const userDetails = {
                    name: username,
                    score: score
                };

                const options = {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(userDetails),
                };

                const response = await fetch("https://snake.howbout.app/api/onur/high-scores", options);

                let htmlResponse = document.querySelector('.response')

                response.status === 201 ?
                    htmlResponse.innerText = 'Successfully Sent to the Leaderboard - Click Leaderboard to See!'
                    : htmlResponse.innerText = 'Error Storing Your Score in the Leaderboard - Please try again'

            } catch (err) {
                console.log(err);
                document.querySelector('.response').innerText = err
            }
        }
    </script>
</html>