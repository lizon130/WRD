@extends('backend.layout.app')

@section('title', 'Entertainment Hub')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="gameTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="chess-tab" data-tab="chess" href="javascript:void(0);">
                                    <i class="fa-solid fa-chess-queen"></i> Chess Game
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="snake-tab" data-tab="snake" href="javascript:void(0);">
                                    <i class="fa-solid fa-snake"></i> Snake Game
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Chess Game Tab Content -->
                        <div id="chess-content" class="tab-content" style="display: block;">
                            <div class="chess-game-container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="chess-board-container" style="max-width: 500px; margin: 0 auto;">
                                            <div class="board-controls mb-3 text-center">
                                                <button class="btn btn-success" id="newGameBtn">
                                                    <i class="fa-solid fa-plus"></i> New Game
                                                </button>
                                                <button class="btn btn-warning" id="difficultyBtn">
                                                    <i class="fa-solid fa-brain"></i> Difficulty: <span
                                                        id="difficultyLevel">Medium</span>
                                                </button>
                                                <button class="btn btn-info" id="flipBoardBtn">
                                                    <i class="fa-solid fa-rotate"></i> Flip Board
                                                </button>
                                            </div>

                                            <!-- Game Status -->
                                            <div class="game-status mb-3 p-2 text-center"
                                                style="background: #f8f9fa; border-radius: 4px; font-weight: bold;">
                                                <span id="gameStatusMessage">Your turn (White)</span>
                                                <span id="checkWarning" style="color: red; display: none;">⚠️ CHECK!</span>
                                            </div>

                                            <!-- Chess Board Grid -->
                                            <div id="chessBoard"
                                                style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 0; border: 2px solid #333;">
                                            </div>

                                            <!-- Coordinates -->
                                            <div class="text-center mt-2" style="color: #666;">
                                                <span>a</span> <span>b</span> <span>c</span> <span>d</span>
                                                <span>e</span> <span>f</span> <span>g</span> <span>h</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Game Info -->
                                        <div class="game-info p-3" style="background: #f8f9fa; border-radius: 8px;">
                                            <h4>Game Status</h4>

                                            <div class="player-info mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div
                                                        style="width: 20px; height: 20px; background: white; border: 2px solid #333; border-radius: 50%; margin-right: 10px;">
                                                    </div>
                                                    <strong>You (White)</strong>
                                                    <span id="playerStatus" class="badge bg-success ms-2">Your Turn</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        style="width: 20px; height: 20px; background: black; border: 2px solid #333; border-radius: 50%; margin-right: 10px;">
                                                    </div>
                                                    <strong>Computer (Black)</strong>
                                                    <span id="computerStatus" class="badge bg-secondary ms-2">Waiting</span>
                                                </div>
                                            </div>

                                            <p><strong>Move #:</strong> <span id="moveDisplay">0</span></p>
                                            <p><strong>Check:</strong> <span id="checkDisplay"
                                                    style="color: green;">No</span></p>

                                            <hr>

                                            <h5>Move History</h5>
                                            <div id="moveHistory"
                                                style="height: 200px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace;">
                                                <!-- Moves will appear here -->
                                            </div>

                                            <hr>

                                            <h5>Captured Pieces</h5>
                                            <div id="capturedPieces"
                                                style="min-height: 40px; padding: 5px; background: white; border: 1px solid #ddd; border-radius: 4px;">
                                                <!-- Captured pieces will appear here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Snake Game Tab Content -->
                        <div id="snake-content" class="tab-content" style="display: none;">
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
                                                            <span class="arrow-key"><i
                                                                    class="fas fa-arrow-left"></i></span>
                                                            <span class="arrow-key"><i
                                                                    class="fas fa-arrow-down"></i></span>
                                                            <span class="arrow-key"><i
                                                                    class="fas fa-arrow-right"></i></span>
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
                                                    <li><i class="fas fa-tachometer-alt"></i> Speed increases every 2 foods
                                                    </li>
                                                    <li><i class="fas fa-ban"></i> Don't hit walls or yourself</li>
                                                    <li><i class="fas fa-star"></i> Reach higher levels for bonuses</li>
                                                </ul>
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

        /* Tab Styles */
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            color: #495057;
            cursor: pointer;
            text-decoration: none;
        }

        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: bold;
        }

        .tab-content {
            padding: 20px 0;
        }

        /* Chess Game Styles */
        .square {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .square.light {
            background-color: #f0d9b5;
        }

        .square.dark {
            background-color: #b58863;
        }

        .square.selected {
            background-color: #7a9bcb !important;
            box-shadow: inset 0 0 0 3px #4299e1;
        }

        .square.valid-move::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: rgba(0, 255, 0, 0.3);
            border-radius: 50%;
        }

        .square.valid-capture::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 0, 0, 0.3);
            border-radius: 50%;
        }

        .square.last-move {
            background-color: rgba(255, 255, 0, 0.2) !important;
        }

        .square.check {
            background-color: rgba(255, 0, 0, 0.3) !important;
            animation: pulse 1s infinite;
        }

        .piece {
            font-size: 45px;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .piece:hover {
            transform: scale(1.1);
        }

        .piece.white {
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .piece.black {
            color: #000;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }

        .thinking {
            animation: pulse 1s infinite;
        }

        .captured-piece {
            display: inline-block;
            font-size: 24px;
            margin: 2px;
        }

        .check-alert {
            color: red;
            font-weight: bold;
            animation: blink 1s infinite;
        }

        /* Snake Game Styles */
        .snake-game-container {
            max-width: 1000px;
            margin: 0 auto;
        }

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

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }

            100% {
                opacity: 1;
            }
        }

        .game-over {
            animation: pulse 0.5s ease;
        }

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
        // Chess AI Class
        class ChessAI {
            constructor(difficulty = 'medium') {
                this.difficulty = difficulty;
                this.pieceValues = {
                    'p': 1,
                    'n': 3,
                    'b': 3,
                    'r': 5,
                    'q': 9,
                    'k': 100
                };
            }

            setDifficulty(difficulty) {
                this.difficulty = difficulty;
            }

            getBestMove(board, gameInstance) {
                const moves = this.getAllValidMoves(board, 'b', gameInstance);

                if (moves.length === 0) return null;

                switch (this.difficulty) {
                    case 'easy':
                        return this.getRandomMove(moves);
                    case 'medium':
                        return this.getMediumMove(board, moves, gameInstance);
                    case 'hard':
                        return this.getBestMoveEvaluation(board, moves, gameInstance);
                    default:
                        return this.getRandomMove(moves);
                }
            }

            getRandomMove(moves) {
                return moves[Math.floor(Math.random() * moves.length)];
            }

            getMediumMove(board, moves, gameInstance) {
                let bestMove = null;
                let bestValue = -Infinity;

                for (const move of moves) {
                    let value = 0;

                    if (board[move.to.row][move.to.col]) {
                        const capturedPiece = board[move.to.row][move.to.col];
                        value += this.pieceValues[capturedPiece.toLowerCase()] * 10;
                    }

                    if (value > bestValue) {
                        bestValue = value;
                        bestMove = move;
                    }
                }

                return bestMove || this.getRandomMove(moves);
            }

            getBestMoveEvaluation(board, moves, gameInstance) {
                let bestMove = null;
                let bestValue = -Infinity;

                for (const move of moves) {
                    const newBoard = this.simulateMove(board, move);
                    const value = this.evaluatePosition(newBoard);

                    if (value > bestValue) {
                        bestValue = value;
                        bestMove = move;
                    }
                }

                return bestMove || this.getRandomMove(moves);
            }

            getAllValidMoves(board, color, gameInstance) {
                const moves = [];
                const isWhite = color === 'w';

                for (let row = 0; row < 8; row++) {
                    for (let col = 0; col < 8; col++) {
                        const piece = board[row][col];
                        if (piece && ((isWhite && piece === piece.toUpperCase()) ||
                                (!isWhite && piece === piece.toLowerCase()))) {
                            const pieceMoves = gameInstance.getValidMovesForPiece(row, col);
                            for (const move of pieceMoves) {
                                moves.push({
                                    from: {
                                        row,
                                        col
                                    },
                                    to: move
                                });
                            }
                        }
                    }
                }

                return moves;
            }

            simulateMove(board, move) {
                const newBoard = board.map(row => [...row]);
                newBoard[move.to.row][move.to.col] = newBoard[move.from.row][move.from.col];
                newBoard[move.from.row][move.from.col] = null;
                return newBoard;
            }

            evaluatePosition(board) {
                let value = 0;

                for (let row = 0; row < 8; row++) {
                    for (let col = 0; col < 8; col++) {
                        const piece = board[row][col];
                        if (piece) {
                            const pieceValue = this.pieceValues[piece.toLowerCase()] || 0;
                            if (piece === piece.toLowerCase()) {
                                value += pieceValue;
                            } else {
                                value -= pieceValue;
                            }
                        }
                    }
                }

                return value;
            }
        }

        // Chess Game Class
        // Replace the ChessGame class with this fixed playable version:

        class ChessGame {
            constructor() {
                this.board = [];
                this.selectedRow = null;
                this.selectedCol = null;
                this.currentPlayer = 'w';
                this.moveCount = 0;
                this.moveHistory = [];
                this.flipped = false;
                this.gameActive = true;
                this.capturedPieces = {
                    w: [],
                    b: []
                };
                this.lastMove = null;
                this.ai = new ChessAI('medium');
                this.isComputerThinking = false;
                this.checkmate = false;
                this.stalemate = false;

                this.initBoard();
                this.renderBoard();
                this.setupEventListeners();
            }

            initBoard() {
                this.board = [
                    ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
                    ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
                    [null, null, null, null, null, null, null, null],
                    [null, null, null, null, null, null, null, null],
                    [null, null, null, null, null, null, null, null],
                    [null, null, null, null, null, null, null, null],
                    ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
                    ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
                ];
            }

            setupEventListeners() {
                document.getElementById('newGameBtn').addEventListener('click', () => this.newGame());
                document.getElementById('flipBoardBtn').addEventListener('click', () => this.flipBoard());

                const difficultyBtn = document.getElementById('difficultyBtn');
                if (difficultyBtn) {
                    difficultyBtn.addEventListener('click', () => this.cycleDifficulty());
                }
            }

            cycleDifficulty() {
                const difficulties = ['easy', 'medium', 'hard'];
                const currentIndex = difficulties.indexOf(this.ai.difficulty);
                const nextIndex = (currentIndex + 1) % difficulties.length;
                const newDifficulty = difficulties[nextIndex];

                this.ai.setDifficulty(newDifficulty);
                document.getElementById('difficultyLevel').textContent =
                    newDifficulty.charAt(0).toUpperCase() + newDifficulty.slice(1);
            }

            renderBoard() {
                const boardElement = document.getElementById('chessBoard');
                if (!boardElement) return;

                boardElement.innerHTML = '';

                let rows = this.flipped ? [0, 1, 2, 3, 4, 5, 6, 7] : [7, 6, 5, 4, 3, 2, 1, 0];

                for (let i of rows) {
                    for (let j = 0; j < 8; j++) {
                        const square = document.createElement('div');
                        square.className = `square ${(i + j) % 2 === 0 ? 'light' : 'dark'}`;
                        square.dataset.row = i;
                        square.dataset.col = j;

                        if (this.selectedRow === i && this.selectedCol === j) {
                            square.classList.add('selected');
                        }

                        if (this.lastMove &&
                            ((this.lastMove.from.row === i && this.lastMove.from.col === j) ||
                                (this.lastMove.to.row === i && this.lastMove.to.col === j))) {
                            square.classList.add('last-move');
                        }

                        const piece = this.board[i][j];
                        if (piece) {
                            const pieceSpan = document.createElement('span');
                            pieceSpan.className = `piece ${piece === piece.toUpperCase() ? 'white' : 'black'}`;
                            pieceSpan.innerHTML = this.getPieceSymbol(piece);
                            square.appendChild(pieceSpan);
                        }

                        // Show valid moves if a piece is selected
                        if (this.gameActive && this.selectedRow !== null && this.selectedCol !== null &&
                            this.currentPlayer === 'w' && !this.isComputerThinking) {
                            const moves = this.getValidMovesForPiece(this.selectedRow, this.selectedCol);
                            if (moves.some(m => m.row === i && m.col === j)) {
                                if (this.board[i][j]) {
                                    square.classList.add('valid-capture');
                                } else {
                                    square.classList.add('valid-move');
                                }
                            }
                        }

                        square.addEventListener('click', () => this.handleSquareClick(i, j));
                        boardElement.appendChild(square);
                    }
                }

                this.updateStatus();
                this.checkForCheck();
            }

            getPieceSymbol(piece) {
                const symbols = {
                    'K': '♔',
                    'Q': '♕',
                    'R': '♖',
                    'B': '♗',
                    'N': '♘',
                    'P': '♙',
                    'k': '♚',
                    'q': '♛',
                    'r': '♜',
                    'b': '♝',
                    'n': '♞',
                    'p': '♟'
                };
                return symbols[piece] || '';
            }

            handleSquareClick(row, col) {
                // Only allow moves when it's player's turn and game is active
                if (!this.gameActive || this.currentPlayer !== 'w' || this.isComputerThinking) {
                    return;
                }

                const piece = this.board[row][col];

                // If no piece selected
                if (this.selectedRow === null || this.selectedCol === null) {
                    // Select piece if it belongs to player (white)
                    if (piece && piece === piece.toUpperCase()) {
                        this.selectedRow = row;
                        this.selectedCol = col;
                        this.renderBoard();
                    }
                    return;
                }

                // If same square clicked, deselect
                if (this.selectedRow === row && this.selectedCol === col) {
                    this.selectedRow = null;
                    this.selectedCol = null;
                    this.renderBoard();
                    return;
                }

                // Try to move
                const moved = this.movePiece(this.selectedRow, this.selectedCol, row, col);

                // Deselect after move attempt
                this.selectedRow = null;
                this.selectedCol = null;

                if (moved) {
                    this.renderBoard();

                    // Check for checkmate or stalemate after move
                    if (this.isCheckmate('b')) {
                        this.gameActive = false;
                        this.checkmate = true;
                        this.showGameOver('Checkmate! You win!');
                        return;
                    } else if (this.isStalemate('b')) {
                        this.gameActive = false;
                        this.stalemate = true;
                        this.showGameOver('Stalemate! Game drawn!');
                        return;
                    }

                    // Computer's turn
                    setTimeout(() => this.computerMove(), 500);
                } else {
                    // If move failed, select new piece if it belongs to player
                    if (piece && piece === piece.toUpperCase()) {
                        this.selectedRow = row;
                        this.selectedCol = col;
                    }
                    this.renderBoard();
                }
            }

            computerMove() {
                if (!this.gameActive || this.currentPlayer !== 'b') return;

                this.isComputerThinking = true;
                this.updateStatus();

                setTimeout(() => {
                    const move = this.ai.getBestMove(this.board, this);

                    if (move) {
                        const moved = this.movePiece(move.from.row, move.from.col, move.to.row, move.to.col);

                        if (moved) {
                            this.renderBoard();

                            // Check for checkmate or stalemate after computer move
                            if (this.isCheckmate('w')) {
                                this.gameActive = false;
                                this.checkmate = true;
                                this.showGameOver('Checkmate! Computer wins!');
                            } else if (this.isStalemate('w')) {
                                this.gameActive = false;
                                this.stalemate = true;
                                this.showGameOver('Stalemate! Game drawn!');
                            }
                        }
                    } else {
                        // No valid moves - check if computer is in checkmate or stalemate
                        if (this.isInCheck(this.board, 'b')) {
                            this.gameActive = false;
                            this.checkmate = true;
                            this.showGameOver('Checkmate! You win!');
                        } else {
                            this.gameActive = false;
                            this.stalemate = true;
                            this.showGameOver('Stalemate! Game drawn!');
                        }
                    }

                    this.isComputerThinking = false;
                    this.updateStatus();
                }, 500);
            }

            movePiece(fromRow, fromCol, toRow, toCol) {
                const piece = this.board[fromRow][fromCol];
                if (!piece) return false;

                const isWhite = piece === piece.toUpperCase();

                // Check if it's the right player's turn
                if ((isWhite && this.currentPlayer !== 'w') || (!isWhite && this.currentPlayer !== 'b')) {
                    return false;
                }

                // PREVENT CAPTURING KINGS - this is the key fix
                const targetPiece = this.board[toRow][toCol];
                if (targetPiece && targetPiece.toLowerCase() === 'k') {
                    console.log('Cannot capture the king! Game should end with checkmate.');
                    return false;
                }

                // Get all possible moves for this piece (without check validation for now)
                const possibleMoves = this.getValidMovesForPiece(fromRow, fromCol);
                const isValid = possibleMoves.some(m => m.row === toRow && m.col === toCol);

                if (!isValid) return false;

                // Capture piece if exists (but never a king - already prevented above)
                if (targetPiece) {
                    const capturedColor = targetPiece === targetPiece.toUpperCase() ? 'w' : 'b';
                    this.capturedPieces[capturedColor].push(targetPiece);
                    this.updateCapturedPieces();
                }

                // Move the piece
                this.board[toRow][toCol] = piece;
                this.board[fromRow][fromCol] = null;

                // Handle pawn promotion
                if (piece.toLowerCase() === 'p' && (toRow === 0 || toRow === 7)) {
                    this.board[toRow][toCol] = isWhite ? 'Q' : 'q';
                }

                // Record move
                this.recordMove(fromRow, fromCol, toRow, toCol, piece, targetPiece);

                // Switch player
                this.currentPlayer = this.currentPlayer === 'w' ? 'b' : 'w';
                this.moveCount++;
                this.lastMove = {
                    from: {
                        row: fromRow,
                        col: fromCol
                    },
                    to: {
                        row: toRow,
                        col: toCol
                    }
                };

                return true;
            }

            getValidMovesForPiece(row, col) {
                const piece = this.board[row][col];
                if (!piece) return [];

                const moves = [];
                const pieceType = piece.toLowerCase();
                const isWhite = piece === piece.toUpperCase();

                // Pawn moves
                if (pieceType === 'p') {
                    const direction = isWhite ? -1 : 1;
                    const startRow = isWhite ? 6 : 1;

                    // Move forward one
                    if (this.isValidSquare(row + direction, col) && !this.board[row + direction][col]) {
                        moves.push({
                            row: row + direction,
                            col
                        });

                        // Move forward two from start
                        if (row === startRow && !this.board[row + 2 * direction][col]) {
                            moves.push({
                                row: row + 2 * direction,
                                col
                            });
                        }
                    }

                    // Capture diagonally
                    [-1, 1].forEach(offset => {
                        const newCol = col + offset;
                        if (this.isValidSquare(row + direction, newCol)) {
                            const target = this.board[row + direction][newCol];
                            if (target && ((isWhite && target === target.toLowerCase()) ||
                                    (!isWhite && target === target.toUpperCase()))) {
                                moves.push({
                                    row: row + direction,
                                    col: newCol
                                });
                            }
                        }
                    });
                }

                // Knight moves
                if (pieceType === 'n') {
                    const offsets = [
                        [-2, -1],
                        [-2, 1],
                        [-1, -2],
                        [-1, 2],
                        [1, -2],
                        [1, 2],
                        [2, -1],
                        [2, 1]
                    ];

                    offsets.forEach(([dr, dc]) => {
                        const newRow = row + dr;
                        const newCol = col + dc;
                        if (this.isValidSquare(newRow, newCol)) {
                            const target = this.board[newRow][newCol];
                            if (!target || ((isWhite && target === target.toLowerCase()) ||
                                    (!isWhite && target === target.toUpperCase()))) {
                                moves.push({
                                    row: newRow,
                                    col: newCol
                                });
                            }
                        }
                    });
                }

                // Rook moves
                if (pieceType === 'r' || pieceType === 'q') {
                    this.addLineMoves(row, col, isWhite, moves, [
                        [-1, 0],
                        [1, 0],
                        [0, -1],
                        [0, 1]
                    ]);
                }

                // Bishop moves
                if (pieceType === 'b' || pieceType === 'q') {
                    this.addLineMoves(row, col, isWhite, moves, [
                        [-1, -1],
                        [-1, 1],
                        [1, -1],
                        [1, 1]
                    ]);
                }

                // King moves
                if (pieceType === 'k') {
                    for (let dr = -1; dr <= 1; dr++) {
                        for (let dc = -1; dc <= 1; dc++) {
                            if (dr === 0 && dc === 0) continue;
                            const newRow = row + dr;
                            const newCol = col + dc;
                            if (this.isValidSquare(newRow, newCol)) {
                                const target = this.board[newRow][newCol];
                                if (!target || ((isWhite && target === target.toLowerCase()) ||
                                        (!isWhite && target === target.toUpperCase()))) {
                                    moves.push({
                                        row: newRow,
                                        col: newCol
                                    });
                                }
                            }
                        }
                    }
                }

                return moves;
            }

            addLineMoves(row, col, isWhite, moves, directions) {
                directions.forEach(([dr, dc]) => {
                    for (let i = 1; i < 8; i++) {
                        const newRow = row + dr * i;
                        const newCol = col + dc * i;

                        if (!this.isValidSquare(newRow, newCol)) break;

                        const target = this.board[newRow][newCol];
                        if (!target) {
                            moves.push({
                                row: newRow,
                                col: newCol
                            });
                        } else {
                            if ((isWhite && target === target.toLowerCase()) ||
                                (!isWhite && target === target.toUpperCase())) {
                                moves.push({
                                    row: newRow,
                                    col: newCol
                                });
                            }
                            break;
                        }
                    }
                });
            }

            isValidSquare(row, col) {
                return row >= 0 && row < 8 && col >= 0 && col < 8;
            }

            findKing(board, color) {
                const kingChar = color === 'w' ? 'K' : 'k';
                for (let row = 0; row < 8; row++) {
                    for (let col = 0; col < 8; col++) {
                        if (board[row][col] === kingChar) {
                            return {
                                row,
                                col
                            };
                        }
                    }
                }
                return null;
            }

            isSquareAttacked(board, row, col, byWhite) {
                // Check for pawn attacks
                const pawnDir = byWhite ? 1 : -1;
                const pawnChar = byWhite ? 'p' : 'P';

                if (this.isValidSquare(row - pawnDir, col - 1) &&
                    board[row - pawnDir]?.[col - 1] === pawnChar) return true;
                if (this.isValidSquare(row - pawnDir, col + 1) &&
                    board[row - pawnDir]?.[col + 1] === pawnChar) return true;

                // Check for knight attacks
                const knightMoves = [
                    [-2, -1],
                    [-2, 1],
                    [-1, -2],
                    [-1, 2],
                    [1, -2],
                    [1, 2],
                    [2, -1],
                    [2, 1]
                ];

                for (const [dr, dc] of knightMoves) {
                    const newRow = row + dr;
                    const newCol = col + dc;
                    if (this.isValidSquare(newRow, newCol)) {
                        const piece = board[newRow][newCol];
                        const knightChar = byWhite ? 'n' : 'N';
                        if (piece === knightChar) return true;
                    }
                }

                // Check for sliding pieces (rook, bishop, queen)
                const directions = [
                    [-1, 0],
                    [1, 0],
                    [0, -1],
                    [0, 1], // Rook directions
                    [-1, -1],
                    [-1, 1],
                    [1, -1],
                    [1, 1] // Bishop directions
                ];

                for (const [dr, dc] of directions) {
                    for (let i = 1; i < 8; i++) {
                        const newRow = row + dr * i;
                        const newCol = col + dc * i;

                        if (!this.isValidSquare(newRow, newCol)) break;

                        const piece = board[newRow][newCol];
                        if (piece) {
                            const isOpponent = byWhite ?
                                piece === piece.toLowerCase() :
                                piece === piece.toUpperCase();

                            if (isOpponent) {
                                const pieceType = piece.toLowerCase();
                                if (pieceType === 'q') return true;
                                if (Math.abs(dr) === Math.abs(dc) && pieceType === 'b') return true;
                                if ((dr === 0 || dc === 0) && pieceType === 'r') return true;
                            }
                            break;
                        }
                    }
                }

                // Check for king attacks
                for (let dr = -1; dr <= 1; dr++) {
                    for (let dc = -1; dc <= 1; dc++) {
                        if (dr === 0 && dc === 0) continue;
                        const newRow = row + dr;
                        const newCol = col + dc;
                        if (this.isValidSquare(newRow, newCol)) {
                            const piece = board[newRow][newCol];
                            const kingChar = byWhite ? 'k' : 'K';
                            if (piece === kingChar) return true;
                        }
                    }
                }

                return false;
            }

            isInCheck(board, color) {
                const kingPos = this.findKing(board, color);
                if (!kingPos) return false;
                return this.isSquareAttacked(board, kingPos.row, kingPos.col, color !== 'w');
            }

            isCheckmate(color) {
                // If king is not in check, it's not checkmate
                if (!this.isInCheck(this.board, color)) {
                    return false;
                }

                // Check if any move can get out of check
                return !this.hasAnyLegalMove(color);
            }

            isStalemate(color) {
                // If king is in check, it's not stalemate
                if (this.isInCheck(this.board, color)) {
                    return false;
                }

                // Check if any legal move exists
                return !this.hasAnyLegalMove(color);
            }

            hasAnyLegalMove(color) {
                const isWhite = color === 'w';

                for (let row = 0; row < 8; row++) {
                    for (let col = 0; col < 8; col++) {
                        const piece = this.board[row][col];
                        if (piece && ((isWhite && piece === piece.toUpperCase()) ||
                                (!isWhite && piece === piece.toLowerCase()))) {
                            const moves = this.getValidMovesForPiece(row, col);

                            // Filter out moves that would capture king
                            const legalMoves = moves.filter(move => {
                                const target = this.board[move.row][move.col];
                                return !target || target.toLowerCase() !== 'k';
                            });

                            if (legalMoves.length > 0) {
                                return true;
                            }
                        }
                    }
                }

                return false;
            }

            checkForCheck() {
                const whiteInCheck = this.isInCheck(this.board, 'w');
                const blackInCheck = this.isInCheck(this.board, 'b');

                const checkWarning = document.getElementById('checkWarning');
                const checkDisplay = document.getElementById('checkDisplay');

                if (!checkWarning || !checkDisplay) return;

                if (this.currentPlayer === 'w' && whiteInCheck) {
                    checkWarning.style.display = 'inline';
                    checkDisplay.textContent = 'Yes!';
                    checkDisplay.style.color = 'red';
                } else if (this.currentPlayer === 'b' && blackInCheck) {
                    checkWarning.style.display = 'inline';
                    checkDisplay.textContent = 'Yes!';
                    checkDisplay.style.color = 'red';
                } else {
                    checkWarning.style.display = 'none';
                    checkDisplay.textContent = 'No';
                    checkDisplay.style.color = 'green';
                }
            }

            showGameOver(message) {
                const statusEl = document.getElementById('gameStatusMessage');
                const checkWarning = document.getElementById('checkWarning');

                if (statusEl) {
                    statusEl.textContent = message;
                }

                if (checkWarning) {
                    checkWarning.style.display = 'none';
                }

                // Show alert
                setTimeout(() => {
                    alert(message);
                }, 100);
            }

            recordMove(fromRow, fromCol, toRow, toCol, piece, capturedPiece) {
                const files = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
                const fromFile = files[fromCol];
                const toFile = files[toCol];
                const fromRank = 8 - fromRow;
                const toRank = 8 - toRow;

                const pieceSymbol = piece.toUpperCase().replace('P', '');
                const captureSymbol = capturedPiece ? 'x' : '';
                const checkSymbol = this.isInCheck(this.board, this.currentPlayer === 'w' ? 'b' : 'w') ? '+' : '';
                const moveStr = `${pieceSymbol}${captureSymbol}${toFile}${toRank}${checkSymbol}`;

                this.moveHistory.push({
                    number: Math.floor(this.moveHistory.length / 2) + 1,
                    move: moveStr,
                    player: piece === piece.toUpperCase() ? 'w' : 'b'
                });

                this.updateMoveHistory();
            }

            updateMoveHistory() {
                const historyEl = document.getElementById('moveHistory');
                if (historyEl) {
                    let html = '';
                    for (let i = 0; i < this.moveHistory.length; i += 2) {
                        const moveNum = Math.floor(i / 2) + 1;
                        const whiteMove = this.moveHistory[i] ? this.moveHistory[i].move : '';
                        const blackMove = this.moveHistory[i + 1] ? this.moveHistory[i + 1].move : '';

                        html += `<div><strong>${moveNum}.</strong> ${whiteMove} ${blackMove}</div>`;
                    }
                    historyEl.innerHTML = html || 'No moves yet';
                }

                document.getElementById('moveDisplay').textContent = this.moveCount;
            }

            updateCapturedPieces() {
                const capturedEl = document.getElementById('capturedPieces');
                if (capturedEl) {
                    let html = '<div style="display: flex; flex-wrap: wrap; gap: 5px;">';

                    this.capturedPieces.w.forEach(p => {
                        html += `<span class="captured-piece piece black">${this.getPieceSymbol(p)}</span>`;
                    });

                    this.capturedPieces.b.forEach(p => {
                        html += `<span class="captured-piece piece white">${this.getPieceSymbol(p)}</span>`;
                    });

                    html += '</div>';
                    capturedEl.innerHTML = html || 'No captured pieces';
                }
            }

            updateStatus() {
                const statusEl = document.getElementById('gameStatusMessage');
                const playerStatus = document.getElementById('playerStatus');
                const computerStatus = document.getElementById('computerStatus');

                if (!statusEl || !playerStatus || !computerStatus) return;

                if (!this.gameActive) {
                    if (this.checkmate) {
                        statusEl.textContent = this.currentPlayer === 'w' ? 'Checkmate! Computer wins!' :
                            'Checkmate! You win!';
                    } else if (this.stalemate) {
                        statusEl.textContent = 'Stalemate! Game drawn!';
                    }
                    return;
                }

                const inCheck = this.isInCheck(this.board, this.currentPlayer);

                if (this.isComputerThinking) {
                    statusEl.textContent = 'Computer is thinking...';
                    playerStatus.className = 'badge bg-secondary';
                    playerStatus.textContent = 'Waiting';
                    computerStatus.className = 'badge bg-warning';
                    computerStatus.textContent = 'Thinking...';
                } else if (this.currentPlayer === 'w') {
                    statusEl.textContent = inCheck ? 'YOUR KING IS IN CHECK!' : 'Your turn (White)';
                    playerStatus.className = `badge ${inCheck ? 'bg-danger' : 'bg-success'}`;
                    playerStatus.textContent = inCheck ? 'IN CHECK!' : 'Your Turn';
                    computerStatus.className = 'badge bg-secondary';
                    computerStatus.textContent = 'Waiting';
                } else {
                    statusEl.textContent = inCheck ? "COMPUTER'S KING IS IN CHECK!" : "Computer's turn (Black)";
                    playerStatus.className = 'badge bg-secondary';
                    playerStatus.textContent = 'Waiting';
                    computerStatus.className = `badge ${inCheck ? 'bg-danger' : 'bg-warning'}`;
                    computerStatus.textContent = inCheck ? 'IN CHECK!' : 'Thinking...';
                }
            }

            newGame() {
                this.initBoard();
                this.selectedRow = null;
                this.selectedCol = null;
                this.currentPlayer = 'w';
                this.moveCount = 0;
                this.moveHistory = [];
                this.gameActive = true;
                this.checkmate = false;
                this.stalemate = false;
                this.capturedPieces = {
                    w: [],
                    b: []
                };
                this.lastMove = null;
                this.isComputerThinking = false;

                this.renderBoard();
                this.updateMoveHistory();
                this.updateCapturedPieces();
                this.updateStatus();
            }

            flipBoard() {
                this.flipped = !this.flipped;
                this.renderBoard();
            }
        }

        // Snake Game Class
        class SnakeGame {
            constructor() {
                this.canvas = document.getElementById('gameCanvas');
                this.ctx = this.canvas ? this.canvas.getContext('2d') : null;
                this.scoreElement = document.getElementById('score');
                this.levelElement = document.getElementById('level');
                this.highScoreElement = document.getElementById('highScore');
                this.speedDisplayElement = document.getElementById('speedDisplay');
                this.speedLevelElement = document.getElementById('speedLevel');
                this.speedLevelText = document.getElementById('speedLevelText');
                this.startBtn = document.getElementById('startBtn');
                this.pauseBtn = document.getElementById('pauseBtn');
                this.resetBtn = document.getElementById('resetBtn');

                if (!this.canvas) return;

                this.gridSize = 20;
                this.tileCount = this.canvas.width / this.gridSize;

                this.baseSpeed = 180;
                this.currentSpeed = this.baseSpeed;
                this.minSpeed = 60;
                this.speedIncreaseRate = 15;
                this.foodsForSpeedUp = 2;

                this.snake = [{
                    x: 15,
                    y: 15
                }];
                this.direction = {
                    x: 0,
                    y: 0
                };
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
                if (!this.highScoreElement) return;
                this.highScoreElement.textContent = this.highScore;
                this.generateFood();
                this.setupEventListeners();
                this.draw();
                this.updateSpeedIndicator();
            }

            setupEventListeners() {
                if (!this.startBtn) return;

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
                            this.direction = {
                                x: 0,
                                y: -1
                            };
                        }
                        break;
                    case 'ArrowDown':
                        if (this.direction.y === 0) {
                            this.direction = {
                                x: 0,
                                y: 1
                            };
                        }
                        break;
                    case 'ArrowLeft':
                        if (this.direction.x === 0) {
                            this.direction = {
                                x: -1,
                                y: 0
                            };
                        }
                        break;
                    case 'ArrowRight':
                        if (this.direction.x === 0) {
                            this.direction = {
                                x: 1,
                                y: 0
                            };
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
                this.direction = {
                    x: 1,
                    y: 0
                };
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

                this.snake = [{
                    x: 15,
                    y: 15
                }];
                this.direction = {
                    x: 0,
                    y: 0
                };
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

                const head = {
                    ...this.snake[0]
                };
                head.x += this.direction.x;
                head.y += this.direction.y;

                if (head.x < 0 || head.x >= this.tileCount || head.y < 0 || head.y >= this.tileCount) {
                    this.gameOver();
                    return;
                }

                if (this.snake.some(segment => segment.x === head.x && segment.y === head.y)) {
                    this.gameOver();
                    return;
                }

                this.snake.unshift(head);

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
                if (!this.ctx) return;

                const bgGradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
                bgGradient.addColorStop(0, '#16213e');
                bgGradient.addColorStop(1, '#1a1a2e');
                this.ctx.fillStyle = bgGradient;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

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

                    if (index === 0) {
                        this.ctx.shadowBlur = 0;
                        this.ctx.fillStyle = 'white';
                        const eyeSize = 4;
                        const eyeOffset = 7;

                        if (this.direction.x === 1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.x === -1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === -1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === 1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset, eyeSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        }

                        this.ctx.fillStyle = '#1a1a2e';
                        const pupilSize = 2;
                        if (this.direction.x === 1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset + 2, y + eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset + 2, y + size - eyeOffset, pupilSize, 0, Math.PI *
                                2);
                            this.ctx.fill();
                        } else if (this.direction.x === -1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset - 2, y + eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset - 2, y + size - eyeOffset, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === -1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + eyeOffset - 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + eyeOffset - 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                        } else if (this.direction.y === 1) {
                            this.ctx.beginPath();
                            this.ctx.arc(x + eyeOffset, y + size - eyeOffset + 2, pupilSize, 0, Math.PI * 2);
                            this.ctx.fill();
                            this.ctx.beginPath();
                            this.ctx.arc(x + size - eyeOffset, y + size - eyeOffset + 2, pupilSize, 0, Math.PI *
                                2);
                            this.ctx.fill();
                        }
                    }
                });

                this.ctx.shadowBlur = 0;
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

                if (this.score == this.highScore && this.score > 0) {
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

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chess Game
            if (document.getElementById('chessBoard')) {
                window.chessGame = new ChessGame();
            }

            // Initialize Snake Game
            if (document.getElementById('gameCanvas')) {
                window.snakeGame = new SnakeGame();
            }

            // Tab switching
            const chessTab = document.getElementById('chess-tab');
            const snakeTab = document.getElementById('snake-tab');
            const chessContent = document.getElementById('chess-content');
            const snakeContent = document.getElementById('snake-content');

            if (chessTab) {
                chessTab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Update tab classes
                    chessTab.classList.add('active');
                    snakeTab.classList.remove('active');

                    // Show/hide content
                    chessContent.style.display = 'block';
                    snakeContent.style.display = 'none';
                });
            }

            if (snakeTab) {
                snakeTab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Update tab classes
                    snakeTab.classList.add('active');
                    chessTab.classList.remove('active');

                    // Show/hide content
                    snakeContent.style.display = 'block';
                    chessContent.style.display = 'none';

                    // Redraw snake game if needed
                    if (window.snakeGame) {
                        window.snakeGame.draw();
                    }
                });
            }
        });
    </script>
@endsection