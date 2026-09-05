<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #1a1a2e;
            --text-color: #ffffff;
            --accent-color: #e94560;
            --cat-color: #fca311;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Nunito', sans-serif;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite alternate;
        }

        @keyframes twinkle {
            from { opacity: 0.2; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .content {
            z-index: 10;
            position: relative;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            line-height: 1;
            background: linear-gradient(45deg, #fca311, #e94560);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        p {
            font-size: 1.5rem;
            margin: 10px 0 30px;
            color: #aeb4c0;
        }

        .btn-home {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(233, 69, 96, 0.4);
            border: none;
            cursor: pointer;
            z-index: 20;
            position: relative;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 69, 96, 0.6);
            background: #ff5773;
        }

        /* --- Game Area --- */
        .game-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 200px;
            border-bottom: 4px solid #0f3460;
            margin-top: 20px;
            overflow: hidden;
            z-index: 10;
        }

        .instruction {
            position: absolute;
            top: 10px;
            width: 100%;
            text-align: center;
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }

        .cat {
            position: absolute;
            bottom: 0;
            left: 50px;
            width: 80px;
            height: 60px;
            background-color: var(--cat-color);
            border-radius: 40px 40px 10px 10px;
            transition: bottom 0.1s;
        }

        /* Ears */
        .cat::before, .cat::after {
            content: '';
            position: absolute;
            top: -15px;
            width: 0; 
            height: 0; 
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 25px solid var(--cat-color);
        }
        .cat::before { left: -5px; transform: rotate(-15deg); }
        .cat::after { right: -5px; transform: rotate(15deg); }

        /* Zzz animation for sleeping cat */
        .zzz {
            position: absolute;
            top: -20px;
            right: 0px;
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            opacity: 0;
            animation: sleep 3s linear infinite;
        }

        @keyframes sleep {
            0% { transform: translate(0, 0) scale(0.5); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translate(30px, -50px) scale(1.5); opacity: 0; }
        }

        .obstacle {
            position: absolute;
            bottom: 0;
            right: -50px;
            width: 40px;
            height: 40px;
            background-color: #4ade80; /* Alarm clock color */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .obstacle::after {
            content: '⏰';
            position: absolute;
            top: 5px;
            font-size: 28px;
        }

        .score-display {
            position: absolute;
            top: 10px;
            right: 20px;
            font-weight: bold;
            font-size: 1.2rem;
            color: #fca311;
        }

        /* Jumping class added via JS */
        .jump {
            animation: jumpAnim 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        .jump .zzz {
            display: none; /* Wake up when jumping */
        }

        @keyframes jumpAnim {
            0% { bottom: 0; }
            40% { bottom: 120px; }
            50% { bottom: 130px; }
            60% { bottom: 120px; }
            100% { bottom: 0; }
        }

        /* Screen flash on lose */
        .lose-flash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #e94560;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s;
        }
    </style>
</head>
<body onclick="jump()">

    <div class="stars" id="stars"></div>
    <div class="lose-flash" id="loseFlash"></div>

    <div class="content">
        <h1>404</h1>
        <p>Oops! You woke up the lazy cat.</p>
        <a href="{{ route('home') }}" class="btn-home">Take Me Home</a>
    </div>

    <div class="game-container" id="gameArea">
        <div class="instruction">Tap / Click anywhere to JUMP! Don't let the alarm clock hit the cat.</div>
        <div class="score-display">Score: <span id="score">0</span></div>
        <div class="cat" id="cat">
            <div class="zzz">z</div>
        </div>
        <div class="obstacle" id="obstacle"></div>
    </div>

    <script>
        // Starry background generator
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.width = Math.random() * 3 + 'px';
            star.style.height = star.style.width;
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.setProperty('--duration', (Math.random() * 2 + 1) + 's');
            starsContainer.appendChild(star);
        }

        // Game Logic
        const cat = document.getElementById('cat');
        const obstacle = document.getElementById('obstacle');
        const scoreElement = document.getElementById('score');
        
        let isJumping = false;
        let score = 0;
        let gameSpeed = 5;
        let obstaclePosition = 600; // start off-screen right
        let isGameOver = false;

        // Start game loop
        let gameLoop = setInterval(update, 20);

        function jump() {
            if (isJumping || isGameOver) return;
            isJumping = true;
            cat.classList.add('jump');
            
            setTimeout(() => {
                cat.classList.remove('jump');
                isJumping = false;
            }, 600); // matches animation duration
        }

        // Support Spacebar for jumping
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space') {
                e.preventDefault();
                jump();
            }
        });

        function update() {
            if (isGameOver) return;

            // Move obstacle
            obstaclePosition -= gameSpeed;
            obstacle.style.right = (600 - obstaclePosition) + 'px'; // Move from right to left

            // Reset obstacle when it goes off screen left
            if (obstaclePosition < -50) {
                obstaclePosition = 600; // Reset to right
                score += 10;
                scoreElement.innerText = score;
                // Increase speed slightly
                if (gameSpeed < 15) {
                    gameSpeed += 0.2;
                }
            }

            // Collision Detection
            // Cat x: 50 to 130
            // Obstacle x: obstaclePosition to obstaclePosition + 40
            
            let catBottom = parseInt(window.getComputedStyle(cat).getPropertyValue('bottom'));
            
            // Cat occupies left: 50px, width: 80px (so 50 to 130 in game container)
            // Obstacle is positioned by right offset. 
            // obstacle.style.right = (600 - obstaclePosition)
            // That means its left position is obstaclePosition - 40 (since its width is 40 and container is 600)
            
            // Wait, simpler way: getBoundingClientRect
            let catRect = cat.getBoundingClientRect();
            let obsRect = obstacle.getBoundingClientRect();

            if (
                catRect.right > obsRect.left + 10 && 
                catRect.left < obsRect.right - 10 && 
                catRect.bottom > obsRect.top + 10
            ) {
                // Collision!
                gameOver();
            }
        }

        function gameOver() {
            isGameOver = true;
            clearInterval(gameLoop);
            
            // Flash screen red
            document.getElementById('loseFlash').style.opacity = '1';
            
            // Redirect to home after 1 second
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }
    </script>
</body>
</html>
