class SnakeGame {


    static NUM_ROWS = Math.ceil(window.screen.availHeight / 20);
    static NUM_COLS = Math.ceil(window.screen.availWidth / 20);


    boardCells = [];
    score = 0;
    food = null;
    foodCellColor = null;

    constructor(board, controls) {

        this.board = board;
        this.controls = controls;

        this.scoreCounter = this.controls.querySelector('.score');

        this.initBoard();

        this.snake = new Snake(this);

        window.addEventListener('keydown', (event) => {
            switch (event.key) {
                case 'ArrowLeft':
                case 'a':
                    this.snake.setDirection('left');
                    break;

                case 'ArrowUp':
                case 'w':
                    this.snake.setDirection('up');
                    break;

                case 'ArrowRight':
                case 'd':
                    this.snake.setDirection('right');
                    break;

                case 'ArrowDown':
                case 's':
                    this.snake.setDirection('down');
                    break;

                case 'Escape':
                    this.snake.pause();
                    break;
                // add shift case and make snake go faster
            }
        });

    }

    /**
     * Build the board using rows of cells
     */
    initBoard() {

        // Generate a new row
        const newRow = (rowNum) => {
            const row = document.createElement('div');
            row.classList.add('row');
            row.classList.add('row-' + rowNum);

            return row;
        }
        // Generate a new column
        const newCol = (colNum) => {
            const col = document.createElement('div');
            col.classList.add('col');
            col.classList.add('col-' + colNum);

            return col;
        }

        // For each number of rows make a new row element and fill with columns
        for (let r = 0; r < SnakeGame.NUM_ROWS; r++) {

            const row = newRow(r);
            const boardCellsRow = [];

            // For each number of columns make a new column element and add to the row
            for (let c = 0; c < SnakeGame.NUM_COLS; c++) {

                const col = newCol(c);
                row.appendChild(col);
                boardCellsRow.push(col);

            }

            this.board.appendChild(row);
            this.boardCells.push(boardCellsRow);

        }

        // adding wall colours
        document.querySelectorAll('div.row-0')
            .forEach(el => {
                el.style.backgroundColor = 'grey'
            })

        document.querySelectorAll('div.col-0')
            .forEach(el => {
                el.style.backgroundColor = 'grey'
            })

        document.querySelectorAll(`div.row-${SnakeGame.NUM_ROWS - 1}`)
            .forEach(el => {
                el.style.backgroundColor = 'grey'
            })

        document.querySelectorAll(`div.col-${SnakeGame.NUM_COLS - 1}`)
            .forEach(el => {
                el.style.backgroundColor = 'grey'
            })

        document.querySelectorAll(`div.col`)
            .forEach((el, index) => {
                if (index % 2 == 0) {
                    el.style.backgroundColor = '#DBBEA1FF'
                }
                else {
                    el.style.backgroundColor = '#CEAE77'
                }
            })

    }

    /**
     * Begin the game
     */
    play() {

        this.controls.classList.add('playing');
        this.moving = true
        this.snake.move();
        new Food(this).move();

        var playAudio = new Audio('./assets/audio/mlg-airhorn.mp3');
        playAudio.play();
    }

    /**
     * Restart the game after game over
     */
    restart() {

        this.snake.reset();
        this.controls.classList.remove('game-over');
        this.board.classList.remove('game-over');
        this.play();

    }

    /**
     * Increment the user's score
     */
    increaseScore(amount) {

        this.score += amount;
        this.scoreCounter.innerText = this.score;

    }

    /**
     * End the game
     */
    async gameOver() {
        var gameOverAudio = new Audio('./assets/audio/snake-dies-metal-gear.mp3');
        gameOverAudio.play();

        this.score = 0;
        this.snake.pause();
        // send user data to api
        this.controls.classList.remove('playing');
        this.controls.classList.add('game-over');
        this.board.classList.add('game-over');

    }

}

class Snake {

    static STARTING_EDGE_OFFSET = 20;
    head = null;
    tail = [];
    position = [];
    tailLength = 6;
    direction = 'right';
    speed = 160;
    moving = false;
    movementTimer = null;

    constructor(game) {

        this.game = game;

        this.init();
    }
    /**
     * Place the snake initially
     */
    init() {
        const x = Math.floor(Math.random() * (SnakeGame.NUM_COLS - Snake.STARTING_EDGE_OFFSET)) + (Snake.STARTING_EDGE_OFFSET / 2);
        const y = Math.floor(Math.random() * (SnakeGame.NUM_ROWS - Snake.STARTING_EDGE_OFFSET)) + (Snake.STARTING_EDGE_OFFSET / 2);
        this.head = `${y}-${x}`
        this.tail = [`${y}-${x - 1}`, `${y}-${x - 2}`, `${y}-${x - 3}`, `${y}-${x - 4}`, `${y}-${x - 5}`];
        console.log(this.position)

        const headCell = this.game.boardCells[y][x];
        headCell.classList.add('snake');

        for (let i = 0; i < this.tail.length; i++) {
            let yX = this.tail[i].split('-');
            let y = yX[0]
            let x = yX[1]

            this.game.boardCells[y][x].classList.add('snake');
        }
    }

    /**
     * Move the snake
     */
    move(direction) {

        // If this is the first move, make sure the game isn't paused
        if (!this.moving) {
            this.moving = true;
            this.game.controls.classList.remove('paused');
        }

        // Todo: add the snake moving logic here and check if the snake hits a wall, itself, or food
        let lastCellCoordinates = this.tail.pop()
        let lastCellSplit = lastCellCoordinates.split('-');

        let lastY = parseInt(lastCellSplit[0]);
        let lastX = parseInt(lastCellSplit[1]);

        this.game.boardCells[lastY][lastX].classList.remove('snake');

        // store position of head before change
        let oldHead = this.head

        // coordinates of snake head 
        let head = this.head.split('-');

        let y = parseInt(head[0]);
        let x = parseInt(head[1]);

        if (direction == 'left') {
            x = x - 1;
        }
        if (direction == 'right') {
            x = x + 1;
        }
        if (direction == 'up') {
            y = y - 1;
        }
        if (direction == 'down') {
            y = y + 1;
        }

        let newCoordinates = y + '-' + x;
        this.head = newCoordinates

        // unshifts old position of head into tail
        this.tail.unshift(oldHead)

        this.game.boardCells[y][x].classList.add('snake');

        // check if it hitself
        for (let i = 1; i < this.tail.length; i++) {

            if (this.head === this.tail[i]) {
                this.game.gameOver();
            }
        }

        // checks if hits wall
        if (x > SnakeGame.NUM_COLS - 2 || x < 1 || y > SnakeGame.NUM_ROWS - 2 || y < 1) {
            this.game.gameOver();
        }

        //  checks if it eats food
        if (this.head === this.game.food) {
            var foodAudio = new Audio('./assets/audio/food.mp3');
            foodAudio.play();

            let foodCoords = this.game.food.split('-');

            let foodY = foodCoords[0];
            let foodX = foodCoords[1];

            // remove random colour then remove class 
            document.querySelector('.food').style.backgroundColor = this.game.foodCellColor;
            this.game.boardCells[foodY][foodX].classList.remove('food');

            // push the popped tail back into the snake
            this.tail.push(lastCellCoordinates);
            this.game.boardCells[y][x].classList.add('snake');

            // spawn new Food
            new Food(this.game).move();

            this.game.increaseScore(5);
            this.speed -= 2;
        }

        // Move another step in `this.speed` number of milliseconds
        if (this.moving) {
            this.movementTimer = setTimeout(() => { this.move(this.direction) }, this.speed);
        }

    }

    /**
     * Set the snake's direction
     */
    setDirection(direction) {
        this.direction = direction;

        if (direction == 'left') {
            this.direction = 'left'
        }
        if (direction == 'right') {
            this.direction = 'right'
        }
        if (direction == 'up') {
            this.direction = 'up'
        }
        if (direction == 'down') {
            this.direction = 'down'
        }
    }

    /**
     * Pause the snake's movement
     */
    pause() {
        clearTimeout(this.movementTimer);
        this.moving = false;
        this.game.controls.classList.add('paused');
    }

    /**
     * Reset the snake back to the initial defaults
     */
    reset() {
        // removes last game's snake cells 
        document.querySelectorAll('div.snake')
            .forEach(el => {
                el.classList.remove('snake')
            })

        // removes last game's food 
        document.querySelectorAll('div.food')
            .forEach(el => {
                el.classList.remove('food')
                el.style.backgroundColor = '#fffcfc'
            })

        this.game.scoreCounter.innerText = '0'

        this.tail.length = 0;
        this.tailLength = 6;
        this.direction = 'right';
        this.speed = 160;
        this.moving = false;

        this.init();

    }

}

class Food {

    constructor(game) {
        this.game = game;
        this.color = `hsl(${Math.floor(Math.random() * 360)},100%,50%)`;
    }

    /**
     * Place the food randomly on the board, by adding the class 'food' to one of the cells
     */
    move() {
        // Todo: write this
        const foodX = Math.floor(Math.random() * (SnakeGame.NUM_COLS - Snake.STARTING_EDGE_OFFSET)) + (Snake.STARTING_EDGE_OFFSET / 2);
        const foodY = Math.floor(Math.random() * (SnakeGame.NUM_ROWS - Snake.STARTING_EDGE_OFFSET)) + (Snake.STARTING_EDGE_OFFSET / 2);

        const foodCell = this.game.boardCells[foodY][foodX];
        this.game.food = foodY + '-' + foodX;

        console.log('this is a food cell', foodCell)
        // records previous foodCell color
        this.game.foodCellColor = window.getComputedStyle(foodCell).backgroundColor;

        // this.game.foodCellColor
        foodCell.classList.add('food');
        document.querySelector('.food').style.backgroundColor = this.color;

    }

}