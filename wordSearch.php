<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?msg=Please login first.");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);

// Get level from URL parameter
$level = $_GET['level'] ?? 'medium';

// Set grid size based on level
switch ($level) {
    case 'easy':
        $gridSize = 12;
        break;
    case 'medium': // NORMAL
        $gridSize = 16;
        break;
    case 'hard':
        $gridSize = 20;
        break;
    default:
        $gridSize = 16;
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" type="text/css" href="WordSearch/wordSearch.css" />
    <title>Word Search</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Pass grid size dynamically to JS -->
   <script>
    const GRID_SIZE = <?php echo $gridSize; ?>;
    const GAME_LEVEL= "<?php echo $level; ?>"; // easy | medium | hard
</script>


    <script src="WordSearch/WSlogic.js"></script>
    <script src="WordSearch/WSpath.js"></script>
    <script src="WordSearch/WScontroller.js"></script>
    <script src="WordSearch/WSview.js"></script>
    <script src="WordSearch/WordSearchAILogic.js"></script>

    <style>
        /* Professional button styling */
        .professional-btn {
            padding: 12px 20px;
            background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        .professional-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(78, 205, 196, 0.6);
            background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
            border-color: rgba(255, 255, 255, 0.4);
        }
        .professional-btn:active {
            transform: translateY(-1px) scale(1.02);
        }
        .professional-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .professional-btn:disabled:hover {
            transform: none;
            box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
        }

        /* ================================
           KEEP CSS AS IT IS
           ================================ */
        .welcome-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 20px 35px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
            z-index: 9999;
            white-space: nowrap;
            font-size: 1.6em;
            font-weight: bold;
        }
        .leaderboard-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 12px 20px;
            background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        .leaderboard-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(78, 205, 196, 0.6);
            background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
            border-color: rgba(255, 255, 255, 0.4);
        }
        .leaderboard-btn:active {
            transform: translateY(-1px) scale(1.02);
        }
        #leaderboardPanel {
            position: fixed;
            top: 70px;
            right: 20px;
            width: 300px;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.95), rgba(240, 248, 255, 0.9));
            border: none;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1), 0 5px 20px rgba(78, 205, 196, 0.15);
            z-index: 999;
            overflow: hidden;
            display: none;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(78, 205, 196, 0.2);
            animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        #leaderboardPanel h3 {
            margin: 0 0 20px 0;
            color: #2D3748;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding-bottom: 15px;
            border-bottom: 3px solid transparent;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4) bottom/100% 3px no-repeat;
            background-clip: border-box;
        }
        #leaderboardList {
            padding: 0;
            margin: 0;
            list-style: none;
            max-height: 320px;
            overflow-y: auto;
        }
        #leaderboardList::-webkit-scrollbar {
            width: 8px;
        }
        #leaderboardList::-webkit-scrollbar-track {
            background: linear-gradient(180deg, rgba(78, 205, 196, 0.1), rgba(255, 107, 107, 0.1));
            border-radius: 10px;
        }
        #leaderboardList::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4ECDC4, #FF6B6B);
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        #leaderboardList li {
            position: relative;
            margin: 12px 0;
            padding: 15px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(240, 248, 255, 0.6));
            border-radius: 18px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(78, 205, 196, 0.15);
            backdrop-filter: blur(5px);
        }
        #leaderboardList li:hover {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(255, 107, 107, 0.1));
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.25);
            border-color: rgba(78, 205, 196, 0.3);
        }
        #leaderboardList li::before {
            content: attr(data-rank);
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #4ECDC4, #44A08D);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(78, 205, 196, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.8);
        }
        #leaderboardList li:nth-child(1)::before {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.9);
        }
        #leaderboardList li:nth-child(2)::before {
            background: linear-gradient(135deg, #C0C0C0, #B8B8B8);
            box-shadow: 0 4px 18px rgba(192, 192, 192, 0.5);
        }
        #leaderboardList li:nth-child(3)::before {
            background: linear-gradient(135deg, #CD7F32, #B87333);
            box-shadow: 0 4px 16px rgba(205, 127, 50, 0.5);
        }
        .leaderboard-username {
            font-weight: 700;
            color: #2D3748;
            margin-left: 25px;
            font-size: 15px;
        }
        .leaderboard-score {
            font-weight: 800;
            color: #4ECDC4;
            font-size: 16px;
            background: linear-gradient(135deg, #4ECDC4, #44A08D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        #customMessageBox {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(75, 85, 99, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10001;
        }
        #customMessageBox > div {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            max-width: 24rem;
            width: 100%;
            text-align: center;
        }
        #messageBoxText {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        #messageBoxCloseBtn {
            padding: 0.5rem 1.5rem;
            background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
            color: #fff;
            border-radius: 30px;
            cursor: pointer;
            border: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        #messageBoxCloseBtn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 30px rgba(78, 205, 196, 0.6);
            background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
            border-color: rgba(255, 255, 255, 0.4);
        }
        #messageBoxCloseBtn:active {
            transform: translateY(-1px) scale(1.02);
        }

        :root {
            --cell-size: 36px; /* default, kept unchanged */
        }

        /* Make h1 and h2 transparent to background */
        h1, h2 {
            color: black;
            text-shadow: 0 2px 4px hsla(0, 26%, 96%, 1.00), 0.5);
            background: transparent;
            -webkit-background-clip: text;
            background-clip: text;
        }

        #wordboard {
            display: grid;
            gap: 4px;
            justify-content: center;
            grid-template-columns: repeat(auto-fit, var(--cell-size));
        }
        #wordboard .cell {
            width: var(--cell-size);
            height: var(--cell-size);
            font-size: calc(var(--cell-size) * 0.5);
            line-height: var(--cell-size);
            text-align: center;
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>

<body>
    <div id="customMessageBox">
        <div>
            <p id="messageBoxText"></p>
            <button id="messageBoxCloseBtn">OK</button>
        </div>
    </div>

    <?php if(isset($_GET['welcome']) && $_GET['welcome']=='username'): ?>
        <div class="welcome-alert" id="welcomeAlert">
            👋 Welcome, <?php echo $username; ?>!
        </div>
    <?php endif; ?>

    <button id="leaderboardBtn" class="leaderboard-btn">🏆 Show Leaderboard</button>

    <div id="leaderboardPanel">
        <h3>Top Scores</h3>
        <ol id="leaderboardList"></ol>
    </div>

    <section id="topScreen">
        <button id="return" class="professional-btn" onclick="window.location.href = 'levelSelect.php';">Back</button>
    </section>

    <h1>Word Hunt</h1>
    <h2 id="gameInfo">Click-and-Drag to select words!</h2>
    <h3 id="themeIntro">The theme is: <span id="wordTheme"></span></h3>

    <section id="gameOptions">
        <button id="newGameButton" type="button" class="professional-btn">New Game</button>
        <div id="Timer">Time: 0s</div>
    </section>

    <div class="double" style="display: flex">
        <div id="wordboard"></div>
        <div id="wordlist"></div>
    </div>

    <section id="solve">
        <button id="solveButton" type="button" class="professional-btn">Release the sacred answers!</button>
    </section>

    <script>
        let timer = null;
        let startTime = null;

        function showCustomMessageBox(message) {
            const messageBox = document.getElementById('customMessageBox');
            const messageBoxText = document.getElementById('messageBoxText');
            const messageBoxCloseBtn = document.getElementById('messageBoxCloseBtn');
            if (messageBox && messageBoxText && messageBoxCloseBtn) {
                messageBoxText.textContent = message;
                messageBox.style.display = 'flex';
                messageBoxCloseBtn.onclick = function() {
                    messageBox.style.display = 'none';
                    messageBoxCloseBtn.onclick = null;
                    // Start new game after congratulations message
                    if (message.includes('Congrats') || message.includes('completed')) {
                        $("#solveButton").prop("disabled", false).text("Release the sacred answers!");
                        if (typeof window.restartWordSearch === 'function') {
                            window.restartWordSearch();
                        }
                        startMainTimer();
                    }
                };
            } else {
                alert(message);
            }
        }

        function startMainTimer() {
            clearInterval(timer);
            startTime = Date.now();
            timer = setInterval(() => {
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                $('#Timer').text(`Time: ${elapsed}s`);
            }, 1000);
        }

        function stopMainTimerAndSubmit() {
            clearInterval(timer);
            const endTime = Date.now();
            const timeSpent = Math.floor((endTime - startTime) / 1000);
            $('#Timer').text(`You finished in ${timeSpent} seconds!`);
            $.ajax({
                url: 'submit_score.php',
                type: 'POST',
                data: { score: timeSpent },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showCustomMessageBox(`🎉 Congrats! You completed the game in ${timeSpent} seconds!`);
                        fetchLeaderboard();
                    } else {
                        showCustomMessageBox("Failed to submit score: " + response.message);
                    }
                },
                error: function(){ showCustomMessageBox("Error submitting score."); }
            });
        }

        function fetchLeaderboard() {
            $.get("fetch_leaderboard.php", function(data) {
                $("#leaderboardList").html(data);
            }).fail(function() {
                $("#leaderboardList").html("<li>Failed to load leaderboard.</li>");
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const welcomeAlert = document.getElementById('welcomeAlert');
            if (welcomeAlert) setTimeout(() => { welcomeAlert.style.display = 'none'; }, 3000);

            const leaderboardBtn = document.getElementById('leaderboardBtn');
            const leaderboardPanel = document.getElementById('leaderboardPanel');
            if (leaderboardBtn && leaderboardPanel) {
                leaderboardBtn.addEventListener('click', function() {
                    if (leaderboardPanel.style.display === 'none') {
                        leaderboardPanel.style.display = 'block';
                        this.textContent = '✕ Hide Leaderboard';
                        fetchLeaderboard();
                    } else {
                        leaderboardPanel.style.display = 'none';
                        this.textContent = '🏆 Show Leaderboard';
                    }
                });
            }
            fetchLeaderboard();
        });

        $(document).ready(function() {
            // Initialize Word Search with correct GRID_SIZE
            const controller = new WordSearchController(
                "#wordboard",
                "#wordlist",
                "#solveButton",
                "#newGameButton",
                "#wordTheme",
                "#Timer",
                GRID_SIZE,
                GAME_LEVEL // ✅ PASS LEVEL
            );

            // Set up button handlers
            $("#solveButton").click(function() {
                if (typeof window.wordSearchView !== 'undefined' && typeof window.wordSearchGame !== 'undefined') {
                    window.wordSearchView.solve(window.wordSearchGame.getWordLocations(), window.wordSearchGame.getMatrix());
                    $(this).prop("disabled", true).text("Answers Revealed!");
                }
            });

            $("#newGameButton").click(() => {
                $("#solveButton").prop("disabled", false).text("Release the sacred answers!");
                if (typeof window.restartWordSearch === 'function') {
                    window.restartWordSearch();
                }
                startMainTimer();
            });

            startMainTimer();
            

            window.onWordSearchGameComplete = function() {
                stopMainTimerAndSubmit();
            };
            
        });
    </script>
</body>
</html>
