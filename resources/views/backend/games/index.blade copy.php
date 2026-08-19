@extends('backend.layout.app')

@section('title', 'Snake Game')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Classic Snake</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Leaderboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Settings</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel">
                                <!-- Snake Game Container -->
                                <div class="snake-game-container">
                                    <div class="game-header">
                                        <div class="game-stats">
                                            <div class="stat-item">
                                                <i class="fas fa-star"></i>
                                                <span class="stat-label">Score</span>
                                                <span class="stat-value" id="score">0</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-chart-line"></i>
                                                <span class="stat-label">Level</span>
                                                <span class="stat-value" id="level">1</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-clock"></i>
                                                <span class="stat-label">Speed</span>
                                                <span class="stat-value" id="speedDisplay">1x</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-trophy"></i>
                                                <span class="stat-label">High Score</span>
                                                <span class="stat-value" id="highScore">0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="game-area-wrapper">
                                        <div class="game-area">
                                            <canvas id="gameCanvas" width="600" height="600"></canvas>
                                        </div>
                                        
                                        <!-- Side Panel -->
                                        <div class="game-side-panel">
                                            <div class="power-ups">
                                                <h4><i class="fas fa-bolt"></i> Power Ups</h4>
                                                <div class="power-up-item">
                                                    <span class="power-up-icon">🍎</span>
                                                    <span class="power-up-name">x2 Score</span>
                                                    <span class="power-up-badge">Next: 50pts</span>
                                                </div>
                                                <div class="power-up-item">
                                                    <span class="power-up-icon">⚡</span>
                                                    <span class="power-up-name">Speed Boost</span>
                                                    <span class="power-up-badge">Next: 100pts</span>
                                                </div>
                                                <div class="power-up-item">
                                                    <span class="power-up-icon">🛡️</span>
                                                    <span class="power-up-name">Shield</span>
                                                    <span class="power-up-badge">Next: 200pts</span>
                                                </div>
                                            </div>
                                            
                                            <div class="achievements">
                                                <h4><i class="fas fa-medal"></i> Achievements</h4>
                                                <div class="achievement-item">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <span>First Food</span>
                                                </div>
                                                <div class="achievement-item">
                                                    <i class="fas fa-circle text-warning"></i>
                                                    <span>Speed Demon</span>
                                                </div>
                                                <div class="achievement-item">
                                                    <i class="fas fa-circle text-secondary"></i>
                                                    <span>Marathon Runner</span>
                                                </div>
                                            </div>
                                            
                                            <div class="current-multiplier">
                                                <span>Score Multiplier</span>
                                                <div class="multiplier-value">1.5x</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="game-controls-wrapper">
                                        <div class="game-controls">
                                            <button id="startBtn" class="btn btn-game btn-game-start">
                                                <i class="fas fa-play"></i> Start Game
                                            </button>
                                            <button id="pauseBtn" class="btn btn-game btn-game-pause" disabled>
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                            <button id="resetBtn" class="btn btn-game btn-game-reset">
                                                <i class="fas fa-redo-alt"></i> Reset
                                            </button>
                                        </div>

                                        <div class="speed-indicator">
                                            <div class="speed-label">
                                                <span>Speed Level</span>
                                                <span id="speedLevelText">1/10</span>
                                            </div>
                                            <div class="progress" style="height: 12px;">
                                                <div id="speedLevel" class="progress-bar" role="progressbar"
                                                    style="width: 0%; background: linear-gradient(90deg, #4ade80, #fbbf24, #f87171);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="instructions">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="instruction-group">
                                                    <h5><i class="fas fa-gamepad"></i> Controls</h5>
                                                    <div class="control-buttons">
                                                        <div class="arrow-keys">
                                                            <div class="arrow-row">
                                                                <span class="arrow-key"><i class="fas fa-arrow-up"></i></span>
                                                            </div>
                                                            <div class="arrow-row">
                                                                <span class="arrow-key"><i class="fas fa-arrow-left"></i></span>
                                                                <span class="arrow-key"><i class="fas fa-arrow-down"></i></span>
                                                                <span class="arrow-key"><i class="fas fa-arrow-right"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="space-key">
                                                            <span class="key">SPACE</span> <span>Pause/Resume</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="instruction-group">
                                                    <h5><i class="fas fa-info-circle"></i> Game Rules</h5>
                                                    <ul class="rules-list">
                                                        <li><i class="fas fa-apple-alt"></i> Eat food to grow and score</li>
                                                        <li><i class="fas fa-tachometer-alt"></i> Speed increases every 2 foods</li>
                                                        <li><i class="fas fa-ban"></i> Don't hit walls or yourself</li>
                                                        <li><i class="fas fa-star"></i> Reach higher levels for bonuses</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel">
                                <div class="leaderboard-container">
                                    <h4 class="text-center mb-4">🏆 Top Players 🏆</h4>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Player</th>
                                                <th>Score</th>
                                                <th>Level</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="rank-badge rank-1">1</span></td>
                                                <td><i class="fas fa-crown text-warning"></i> Admin</td>
                                                <td>450</td>
                                                <td>8</td>
                                                <td>2024-01-15</td>
                                            </tr>
                                            <tr>
                                                <td><span class="rank-badge rank-2">2</span></td>
                                                <td><i class="fas fa-user"></i> Player2</td>
                                                <td>320</td>
                                                <td>6</td>
                                                <td>2024-01-14</td>
                                            </tr>
                                            <tr>
                                                <td><span class="rank-badge rank-3">3</span></td>
                                                <td><i class="fas fa-user"></i> Player3</td>
                                                <td>280</td>
                                                <td>5</td>
                                                <td>2024-01-13</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel">
                                <div class="settings-container">
                                    <h4 class="text-center mb-4">⚙️ Game Settings ⚙️</h4>
                                    <div class="settings-form">
                                        <div class="form-group">
                                            <label>Initial Speed</label>
                                            <input type="range" class="form-control-range" min="100" max="300" value="180">
                                            <small class="text-muted">Lower value = faster game</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Grid Size</label>
                                            <select class="form-control">
                                                <option>20x20 (Classic)</option>
                                                <option>25x25 (Large)</option>
                                                <option>30x30 (Expert)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Theme</label>
                                            <select class="form-control">
                                                <option>Dark (Default)</option>
                                                <option>Light</option>
                                                <option>Neon</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --card-bg: #1e1e2f;
            --text-primary: #fff;
            --text-secondary: #b8b8b8;
            --accent-color: #4f9eff;
            --success-color: #4ade80;
            --warning-color: #fbbf24;
            --danger-color: #f87171;
        }

        .snake-game-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Game Header with Stats */
        .game-header {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }

        .game-stats {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            position: relative;
            padding: 0 20px;
        }

        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 2px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .stat-item i {
            font-size: 28px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
            display: block;
        }

        .stat-label {
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }

        .stat-value {
            display: block;
            font-size: 32px;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Game Area Wrapper */
        .game-area-wrapper {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        .game-area {
            flex: 2;
            background: var(--dark-bg);
            border-radius: 20px;
            padding: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        #gameCanvas {
            display: block;
            width: 100%;
            height: auto;
            background: var(--darker-bg);
            border-radius: 15px;
            box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.8);
        }

        /* Side Panel */
        .game-side-panel {
            flex: 1;
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .power-ups h4,
        .achievements h4 {
            color: white;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .power-up-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        .power-up-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .power-up-icon {
            font-size: 24px;
            margin-right: 12px;
        }

        .power-up-name {
            flex: 1;
            color: var(--text-primary);
            font-size: 14px;
        }

        .power-up-badge {
            font-size: 12px;
            padding: 4px 8px;
            background: rgba(79, 158, 255, 0.2);
            border-radius: 20px;
            color: var(--accent-color);
        }

        .achievement-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: var(--text-secondary);
        }

        .achievement-item i {
            width: 20px;
        }

        .current-multiplier {
            background: var(--primary-gradient);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .current-multiplier span {
            display: block;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
        }

        .multiplier-value {
            font-size: 48px;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Game Controls */
        .game-controls-wrapper {
            margin-bottom: 30px;
        }

        .game-controls {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn-game {
            min-width: 140px;
            padding: 15px 25px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-game-start {
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: white;
            box-shadow: 0 10px 20px rgba(74, 222, 128, 0.3);
        }

        .btn-game-pause {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
        }

        .btn-game-reset {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
            box-shadow: 0 10px 20px rgba(248, 113, 113, 0.3);
        }

        .btn-game:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-game:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Speed Indicator */
        .speed-indicator {
            max-width: 500px;
            margin: 0 auto;
        }

        .speed-label {
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            margin-bottom: 5px;
            font-size: 14px;
        }

        /* Instructions */
        .instructions {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .instruction-group h5 {
            color: white;
            margin-bottom: 20px;
        }

        .control-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .arrow-keys {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .arrow-row {
            display: flex;
            gap: 5px;
        }

        .arrow-key {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .space-key {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .key {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rules-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .rules-list li {
            padding: 10px 0;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rules-list li i {
            color: var(--accent-color);
            width: 20px;
        }

        /* Leaderboard Styles */
        .leaderboard-container {
            padding: 20px;
            background: var(--card-bg);
            border-radius: 20px;
        }

        .rank-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .rank-2 {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            color: white;
        }

        .rank-3 {
            background: linear-gradient(135deg, #b45309, #92400e);
            color: white;
        }

        /* Animations */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .game-over {
            animation: pulse 0.5s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .game-area-wrapper {
                flex-direction: column;
            }

            .game-stats {
                flex-direction: column;
            }

            .stat-item:not(:last-child)::after {
                display: none;
            }

            .btn-game {
                min-width: 100px;
                padding: 12px 15px;
                font-size: 14px;
            }
        }
    </style>

    <script>
        class SnakeGame {
            constructor() {
                this.canvas = document.getElementById('gameCanvas');
                this.ctx = this.canvas.getContext('2d');
                this.scoreElement = document.getElementById('score');
                this.levelElement = document.getElementById('level');
                this.highScoreElement = document.getElementById('highScore');
                this.speedDisplayElement = document.getElementById('speedDisplay');
                this.speedLevelElement = document.getElementById('speedLevel');
                this.speedLevelText = document.getElementById('speedLevelText');
                this.startBtn = document.getElementById('startBtn');
                this.pauseBtn = document.getElementById('pauseBtn');
                this.resetBtn = document.getElementById('resetBtn');

                this.gridSize = 20;
                this.tileCount = this.canvas.width / this.gridSize;

                // Game settings
                this.baseSpeed = 180;
                this.currentSpeed = this.baseSpeed;
                this.minSpeed = 60;
                this.speedIncreaseRate = 15;
                this.foodsForSpeedUp = 2;

                this.snake = [{x: 15, y: 15}];
                this.direction = {x: 0, y: 0};
                this.food = {};
                this.score = 0;
                this.level = 1;
                this.foodsEaten = 0;
                this.gameRunning = false;
                this.gamePaused = false;
                this.gameLoop = null;
                this.highScore = localStorage.getItem('snakeHighScore') || 0;

                this.init();
            }

            init() {
                this.highScoreElement.textContent = this.highScore;
                this.generateFood();
                this.setupEventListeners();
                this.draw();
                this.updateSpeedIndicator();
            }

            setupEventListeners() {
                this.startBtn.addEventListener('click', () => this.startGame());
                this.pauseBtn.addEventListener('click', () => this.togglePause());
                this.resetBtn.addEventListener('click', () => this.resetGame());

                document.addEventListener('keydown', (e) => this.handleKeyPress(e));
            }

            handleKeyPress(e) {
                if (!this.gameRunning || this.gamePaused) return;

                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', ' '].includes(e.key)) {
                    e.preventDefault();
                }

                switch (e.key) {
                    case 'ArrowUp':
                        if (this.direction.y === 0) {
                            this.direction = {x: 0, y: -1};
                        }
                        break;
                    case 'ArrowDown':
                        if (this.direction.y === 0) {
                            this.direction = {x: 0, y: 1};
                        }
                        break;
                    case 'ArrowLeft':
                        if (this.direction.x === 0) {
                            this.direction = {x: -1, y: 0};
                        }
                        break;
                    case 'ArrowRight':
                        if (this.direction.x === 0) {
                            this.direction = {x: 1, y: 0};
                        }
                        break;
                    case ' ':
                        e.preventDefault();
                        this.togglePause();
                        break;
                }
            }

            startGame() {
                if (this.gameRunning) return;

                this.gameRunning = true;
                this.gamePaused = false;
                this.direction = {x: 1, y: 0};
                this.currentSpeed = this.baseSpeed;

                this.startBtn.disabled = true;
                this.pauseBtn.disabled = false;

                this.startGameLoop();
            }

            startGameLoop() {
                if (this.gameLoop) clearInterval(this.gameLoop);
                this.gameLoop = setInterval(() => this.update(), this.currentSpeed);
            }

            togglePause() {
                if (!this.gameRunning) return;

                this.gamePaused = !this.gamePaused;
                this.pauseBtn.innerHTML = this.gamePaused ? 
                    '<i class="fas fa-play"></i> Resume' : 
                    '<i class="fas fa-pause"></i> Pause';

                if (!this.gamePaused) {
                    this.startGameLoop();
                }
            }

            resetGame() {
                this.gameRunning = false;
                this.gamePaused = false;

                if (this.gameLoop) {
                    clearInterval(this.gameLoop);
                    this.gameLoop = null;
                }

                this.snake = [{x: 15, y: 15}];
                this.direction = {x: 0, y: 0};
                this.score = 0;
                this.level = 1;
                this.foodsEaten = 0;
                this.currentSpeed = this.baseSpeed;

                this.scoreElement.textContent = this.score;
                this.levelElement.textContent = this.level;
                this.speedDisplayElement.textContent = '1x';

                this.startBtn.disabled = false;
                this.pauseBtn.disabled = true;
                this.pauseBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';

                this.generateFood();
                this.updateSpeedIndicator();
                this.draw();
            }

            generateFood() {
                do {
                    this.food = {
                        x: Math.floor(Math.random() * this.tileCount),
                        y: Math.floor(Math.random() * this.tileCount)
                    };
                } while (this.snake.some(segment => segment.x === this.food.x && segment.y === this.food.y));
            }

            increaseSpeed() {
                const newSpeed = Math.max(this.minSpeed, this.currentSpeed - this.speedIncreaseRate);

                if (newSpeed !== this.currentSpeed) {
                    this.currentSpeed = newSpeed;
                    this.level++;
                    
                    // Calculate speed multiplier (baseSpeed / currentSpeed)
                    const speedMultiplier = (this.baseSpeed / this.currentSpeed).toFixed(1);
                    
                    this.levelElement.textContent = this.level;
                    this.speedDisplayElement.textContent = speedMultiplier + 'x';

                    this.canvas.classList.add('speed-up');
                    setTimeout(() => this.canvas.classList.remove('speed-up'), 300);

                    if (this.gameRunning && !this.gamePaused) {
                        this.startGameLoop();
                    }

                    this.updateSpeedIndicator();
                }
            }

            updateSpeedIndicator() {
                const speedRange = this.baseSpeed - this.minSpeed;
                const currentSpeedDiff = this.baseSpeed - this.currentSpeed;
                const percentage = (currentSpeedDiff / speedRange) * 100;
                this.speedLevelElement.style.width = Math.min(100, percentage) + '%';
                
                const levelFromSpeed = Math.floor((percentage / 100) * 10) + 1;
                this.speedLevelText.textContent = `${levelFromSpeed}/10`;
            }

            update() {
                if (!this.gameRunning || this.gamePaused) return;

                const head = {...this.snake[0]};
                head.x += this.direction.x;
                head.y += this.direction.y;

                // Wall collision
                if (head.x < 0 || head.x >= this.tileCount || head.y < 0 || head.y >= this.tileCount) {
                    this.gameOver();
                    return;
                }

                // Self collision
                if (this.snake.some(segment => segment.x === head.x && segment.y === head.y)) {
                    this.gameOver();
                    return;
                }

                this.snake.unshift(head);

                // Food collision
                if (head.x === this.food.x && head.y === this.food.y) {
                    this.score += 10;
                    this.foodsEaten++;
                    this.scoreElement.textContent = this.score;

                    if (this.score > this.highScore) {
                        this.highScore = this.score;
                        localStorage.setItem('snakeHighScore', this.highScore);
                        this.highScoreElement.textContent = this.highScore;
                    }

                    if (this.foodsEaten % this.foodsForSpeedUp === 0) {
                        this.increaseSpeed();
                    }

                    this.generateFood();
                } else {
                    this.snake.pop();
                }

                this.draw();
            }

            draw() {
                // Clear canvas with gradient background
                const bgGradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
                bgGradient.addColorStop(0, '#16213e');
                bgGradient.addColorStop(1, '#1a1a2e');
                this.ctx.fillStyle = bgGradient;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

                // Draw grid lines with glow effect
                this.ctx.strokeStyle = 'rgba(79, 158, 255, 0.15)';
                this.ctx.lineWidth = 1;
                this.ctx.shadowColor = 'rgba(79, 158, 255, 0.3)';
                this.ctx.shadowBlur = 2;

                for (let i = 0; i <= this.tileCount; i++) {
                    this.ctx.beginPath();
                    this.ctx.moveTo(i * this.gridSize, 0);
                    this.ctx.lineTo(i * this.gridSize, this.canvas.height);
                    this.ctx.stroke();

                    this.ctx.beginPath();
                    this.ctx.moveTo(0, i * this.gridSize);
                    this.ctx.lineTo(this.canvas.width, i * this.gridSize);
                    this.ctx.stroke();
                }

                this.ctx.shadowBlur = 0;

                // Draw snake with gradient and glow
                this.snake.forEach((segment, index) => {
                    const gradient = this.ctx.createRadialGradient(
                        segment.x * this.gridSize + this.gridSize / 2,
                        segment.y * this.gridSize + this.gridSize / 2,
                        0,
                        segment.x * this.gridSize + this.gridSize / 2,
                        segment.y * this.gridSize + this.gridSize / 2,
                        this.gridSize
                    );

                    if (index === 0) {
                        gradient.addColorStop(0, '#4ade80');
                        gradient.addColorStop(1, '#22c55e');
                        this.ctx.shadowColor = '#4ade80';
                        this.ctx.shadowBlur = 15;
                    } else {
                        const intensity = 1 - (index / this.snake.length) * 0.5;
                        gradient.addColorStop(0, `rgba(74, 222, 128, ${intensity})`);
                        gradient.addColorStop(1, `rgba(34, 197, 94, ${intensity})`);
                        this.ctx.shadowColor = 'rgba(74, 222, 128, 0.3)';
                        this.ctx.shadowBlur = 5;
                    }

                    this.ctx.fillStyle = gradient;
                    
                    // Draw rounded squares
                    const x = segment.x * this.gridSize + 2;
                    const y = segment.y * this.gridSize + 2;
                    const size = this.gridSize - 4;
                    const radius = 5;

                    this.ctx.beginPath();
                    this.ctx.moveTo(x + radius, y);
                    this.ctx.lineTo(x + size - radius, y);
                    this.ctx.quadraticCurveTo(x + size, y, x + size, y + radius);
                    this.ctx.lineTo(x + size, y + size - radius);
                    this.ctx.quadraticCurveTo(x + size, y + size, x + size - radius, y + size);
                    this.ctx.lineTo(x + radius, y + size);
                    this.ctx.quadraticCurveTo(x, y + size, x, y + size - radius);
                    this.ctx.lineTo(x, y + radius);
                    this.ctx.quadraticCurveTo(x, y, x + radius, y);
                    this.ctx.closePath();
                    this.ctx.fill();

                    // Draw eyes on head
                    if (index === 0) {
                        this.ctx.shadowBlur = 0;
                        this.ctx.fillStyle = 'white';
                        const eyeSize = 4;
                        const eyeOffset = 7;

                        if (this.direction.x === 1) { // Right
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.x === -1) { // Left
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === -1) { // Up
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === 1) { // Down
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        }

                        // Draw pupils
                        this.ctx.fillStyle = '#1a1a2e';
                        const pupilSize = 2;
                        if (this.direction.x === 1) { // Right
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset + 2, y + eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset + 2, y + size - eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.x === -1) { // Left
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset - 2, y + eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset - 2, y + size - eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === -1) { // Up
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset - 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset - 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === 1) { // Down
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset + 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset + 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        }
                    }
                });

                this.ctx.shadowBlur = 0;

                // Draw food with glow effect
                this.ctx.shadowColor = '#f87171';
                this.ctx.shadowBlur = 20;
                
                const gradient = this.ctx.createRadialGradient(
                    this.food.x * this.gridSize + this.gridSize / 2,
                    this.food.y * this.gridSize + this.gridSize / 2,
                    0,
                    this.food.x * this.gridSize + this.gridSize / 2,
                    this.food.y * this.gridSize + this.gridSize / 2,
                    this.gridSize / 2
                );

                gradient.addColorStop(0, '#f87171');
                gradient.addColorStop(0.7, '#ef4444');
                gradient.addColorStop(1, '#dc2626');

                this.ctx.fillStyle = gradient;
                this.ctx.beginPath();
                this.ctx.arc(
                    this.food.x * this.gridSize + this.gridSize / 2,
                    this.food.y * this.gridSize + this.gridSize / 2,
                    this.gridSize / 2 - 3,
                    0,
                    Math.PI * 2
                );
                this.ctx.fill();

                // Draw sparkle effect on food
                this.ctx.shadowBlur = 0;
                this.ctx.fillStyle = 'rgba(255, 255, 255, 0.6)';
                this.ctx.beginPath();
                this.ctx.arc(
                    this.food.x * this.gridSize + this.gridSize / 2 - 4,
                    this.food.y * this.gridSize + this.gridSize / 2 - 4,
                    2,
                    0,
                    Math.PI * 2
                );
                this.ctx.fill();

                this.ctx.shadowBlur = 0;
            }

            gameOver() {
                this.gameRunning = false;

                if (this.gameLoop) {
                    clearInterval(this.gameLoop);
                    this.gameLoop = null;
                }

                this.canvas.classList.add('game-over');
                setTimeout(() => this.canvas.classList.remove('game-over'), 500);

                // Draw game over overlay
                this.ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

                this.ctx.shadowColor = '#f87171';
                this.ctx.shadowBlur = 20;
                this.ctx.fillStyle = 'white';
                this.ctx.font = 'bold 36px "Segoe UI", Arial, sans-serif';
                this.ctx.textAlign = 'center';
                this.ctx.textBaseline = 'middle';
                this.ctx.fillText('Game Over!', this.canvas.width / 2, this.canvas.height / 2 - 60);

                this.ctx.shadowBlur = 10;
                this.ctx.font = '24px "Segoe UI", Arial, sans-serif';
                this.ctx.fillText(`Score: ${this.score}`, this.canvas.width / 2, this.canvas.height / 2);
                this.ctx.fillText(`Level: ${this.level}`, this.canvas.width / 2, this.canvas.height / 2 + 40);

                if (this.score === this.highScore && this.score > 0) {
                    this.ctx.fillStyle = '#fbbf24';
                    this.ctx.font = '20px "Segoe UI", Arial, sans-serif';
                    this.ctx.fillText('🏆 New High Score! 🏆', this.canvas.width / 2, this.canvas.height / 2 + 90);
                }

                this.ctx.shadowBlur = 0;

                this.startBtn.disabled = false;
                this.pauseBtn.disabled = true;
                this.pauseBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';
            }
        }

        // Initialize the game when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            new SnakeGame();
        });
    </script>
@endsection