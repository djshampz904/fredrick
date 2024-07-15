const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

let balls = [];
let animationId;
let speed = 2;
let isPaused = true;
let score = 0;
const colors = ['red', 'blue', 'green', 'yellow', 'purple', 'orange'];

class Ball {
    constructor(x, y, dx, dy, color) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.color = color;
        this.radius = 7;
        this.strokeWidth = 2;
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
        ctx.lineWidth = this.strokeWidth;
        ctx.strokeStyle = 'black';
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
    }

    isCollidingWith(otherBall) {
        const dx = this.x - otherBall.x;
        const dy = this.y - otherBall.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        return distance < this.radius + otherBall.radius;
    }
}

function addBall() {
    const x = Math.random() * canvas.width;
    const y = Math.random() * canvas.height;
    const dx = (Math.random() - 0.5) * speed;
    const dy = (Math.random() - 0.5) * speed;
    const color = colors[Math.floor(Math.random() * colors.length)];
    balls.push(new Ball(x, y, dx, dy, color));
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Check for collisions and remove colliding balls
    for (let i = 0; i < balls.length; i++) {
        for (let j = i + 1; j < balls.length; j++) {
            if (balls[i].isCollidingWith(balls[j])) {
                // increase score by 5
                score += 5;
                // send score to html
                document.getElementById('score').textContent = score;
                balls.splice(j, 1);
                balls.splice(i, 1);
                score += 5;
                i--; // Adjust index to account for the removed ball
                break;
            }
        }
    }

    balls.forEach(ball => {
        ball.draw();
        ball.update();
    });

    animationId = requestAnimationFrame(animate);
}

document.getElementById('startPauseBtn').addEventListener('click', function () {
    if (isPaused) {
        if (balls.length === 0) {
            addBall(canvas.width / 2, canvas.height / 2);
        }
        animate();
        this.textContent = 'Pause';
    } else {
        cancelAnimationFrame(animationId);
        this.textContent = 'Resume';
    }
    isPaused = !isPaused;
});

document.getElementById('addBallBtn').addEventListener('click', function () {
    addBall();
});

document.getElementById('changeColorBtn').addEventListener('click', function () {
    balls.forEach(ball => {
        ball.color = colors[Math.floor(Math.random() * colors.length)];
    });
});

document.getElementById('restartBtn').addEventListener('click', function () {
    balls = [];
    addBall();
    speed = 2;
    isPaused = true;
    cancelAnimationFrame(animationId);
    document.getElementById('startPauseBtn').textContent = 'Start';
    score = 0;
});

document.getElementById('increaseSpeedBtn').addEventListener('click', function () {
    speed += 1;
    balls.forEach(ball => {
        ball.dx *= 1.1;
        ball.dy *= 1.1;
    });
});

document.getElementById('decreaseSpeedBtn').addEventListener('click', function () {
    speed = Math.max(1, speed - 1);
    balls.forEach(ball => {
        ball.dx *= 0.9;
        ball.dy *= 0.9;
    });
});
