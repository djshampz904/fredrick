const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const startPauseBtn = document.getElementById('startPauseBtn');
const addBallBtn = document.getElementById('addBallBtn');
const changeColorBtn = document.getElementById('changeColorBtn');
const restartBtn = document.getElementById('restartBtn');
const increaseSpeedBtn = document.getElementById('increaseSpeedBtn');
const decreaseSpeedBtn = document.getElementById('decreaseSpeedBtn');
const scoreElement = document.querySelector('#score span');

let balls = [];
let animationId;
let isPaused = true;
let score = 0;

function resizeCanvas() {
    canvas.width = 800;
    canvas.height = 400;
}

resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Ball {
    constructor(x, y, dx, dy, radius, color) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.radius = radius;
        this.color = color;
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.closePath();
    }

    update() {
        if (this.x + this.radius > canvas.width || this.x - this.radius < 0) {
            this.dx = -this.dx;
        }
        if (this.y + this.radius > canvas.height || this.y - this.radius < 0) {
            this.dy = -this.dy;
        }
        this.x += this.dx;
        this.y += this.dy;
        this.draw();
    }
}

function init() {
    balls = [];
    score = 0;
    scoreElement.textContent = score;
    addBall();
}

function addBall() {
    const radius = 7;
    const x = Math.random() * (canvas.width - radius * 2) + radius;
    const y = canvas.height - radius;
    const dx = (Math.random() - 0.5) * 4;
    const dy = -Math.random() * 4 - 1;
    const color = 'green';
    balls.push(new Ball(x, y, dx, dy, radius, color));
}

function animate() {
    if (!isPaused) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        balls.forEach((ball, index) => {
            ball.update();
            checkCollision(ball, index);
        });
        animationId = requestAnimationFrame(animate);
    }
}

function checkCollision(ball, index) {
    for (let i = index + 1; i < balls.length; i++) {
        const otherBall = balls[i];
        const dx = ball.x - otherBall.x;
        const dy = ball.y - otherBall.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance < ball.radius + otherBall.radius) {
            score += 5;
            scoreElement.textContent = score;
            balls.splice(i, 1);
            balls.splice(index, 1);
            break;
        }
    }
}

function changeColor() {
    balls.forEach(ball => {
        ball.color = `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
    });
}

function changeSpeed(factor) {
    balls.forEach(ball => {
        ball.dx *= factor;
        ball.dy *= factor;
    });
}

startPauseBtn.addEventListener('click', () => {
    isPaused = !isPaused;
    if (!isPaused) {
        startPauseBtn.textContent = 'Pause';
        animate();
    } else {
        startPauseBtn.textContent = 'Start';
        cancelAnimationFrame(animationId);
    }
});

addBallBtn.addEventListener('click', addBall);
changeColorBtn.addEventListener('click', changeColor);
restartBtn.addEventListener('click', init);
increaseSpeedBtn.addEventListener('click', () => changeSpeed(1.2));
decreaseSpeedBtn.addEventListener('click', () => changeSpeed(0.8));

init();