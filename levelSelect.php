<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?msg=Please login first.");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Level</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Logout button */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
            border: none;
            padding: 12px 20px;
            border-radius: 30px;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .logout-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(78, 205, 196, 0.6);
            background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .logout-btn:active {
            transform: translateY(-1px) scale(1.02);
        }

        /* Welcome message */
        .welcome-message {
            font-size: 2.8rem;
            font-weight: bold;
            margin-top: 60px;
            text-align: center;
            text-shadow: 0 3px 6px rgba(0,0,0,0.4);
            letter-spacing: 2px;
        }

        /* Game description and image */
        .game-desc-container {
            max-width: 700px;
            text-align: center;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 5;
        }

        .game-desc {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .game-image {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .game-image:hover {
            transform: scale(1.02);
        }

        /* Main title for level selection */
        .main-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 40px 0;
            text-align: center;
            color: #fff;
            text-shadow: 0 4px 8px rgba(0,0,0,0.4);
            letter-spacing: 3px;
            text-transform: uppercase;
            position: relative;
            z-index: 5;
        }

        .main-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4);
            border-radius: 2px;
        }

        /* Difficulty container */
        .levels-wrapper {
            display: flex;
            gap: 30px;
            justify-content: center;
            align-items: flex-start;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .level-container {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(240, 248, 255, 0.1));
            backdrop-filter: blur(15px);
            padding: 2.5rem;
            border-radius: 25px;
            text-align: center;
            width: 340px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3), 0 5px 25px rgba(78, 205, 196, 0.2);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.25);
            position: relative;
            overflow: hidden;
        }

        .level-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transform: rotate(45deg);
            transition: all 0.5s;
            opacity: 0;
        }

        .level-container:hover::before {
            opacity: 1;
        }

        .level-container:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 8px 35px rgba(78, 205, 196, 0.3);
            border-color: rgba(78, 205, 196, 0.4);
        }

        .level-container h3 {
            margin-bottom: 25px;
            font-size: 1.5rem;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .level-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 25px;
            margin: 20px 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
        }

        .level-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(78, 205, 196, 0.3);
        }

        .level-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .level-icon {
            font-size: 2.5rem;
            margin-right: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .level-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .level-description {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
            line-height: 1.5;
            font-weight: 500;
        }

        .level-btn {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .level-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .level-btn:hover::before {
            left: 100%;
        }

        .easy { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .medium { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .hard { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        .level-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .easy:hover { background: linear-gradient(135deg, #27ae60, #229954); }
        .medium:hover { background: linear-gradient(135deg, #e67e22, #d68910); }
        .hard:hover { background: linear-gradient(135deg, #c0392b, #a93226); }

        /* Responsive design for smaller screens */
        @media (max-width: 1024px) {
            .levels-wrapper {
                flex-direction: column;
                align-items: center;
            }
            
            .level-container {
                width: 350px;
            }
        }

        /* Social Media Footer */
        .social-footer {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(240, 248, 255, 0.1));
            backdrop-filter: blur(15px);
            padding: 20px 30px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 5px 15px rgba(78, 205, 196, 0.2);
            z-index: 10;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .social-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
            border-radius: 50%;
        }

        .social-icon:hover::before {
            left: 100%;
        }

        .social-icon:hover {
            transform: translateY(-5px) scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .social-twitter { background: linear-gradient(135deg, #1DA1F2, #0d8bd9); }
        .social-facebook { background: linear-gradient(135deg, #4267B2, #365899); }
        .social-instagram { background: linear-gradient(135deg, #E1306C, #C13584); }
        .social-linkedin { background: linear-gradient(135deg, #0077B5, #005885); }
        .social-github { background: linear-gradient(135deg, #333, #000); }
    </style>
</head>
<body>

<!-- Logout Button -->
<form action="logout.php" method="post">
    <button type="submit" class="logout-btn">Logout</button>
</form>

<!-- Welcome Message -->
<div class="welcome-message">
    👋 Welcome, <?php echo $username; ?>!
</div>

<!-- Game Description and Image -->
<div class="game-desc-container">
    <div class="game-desc">
        🎮 Welcome to Word Hunt - the ultimate brain training game! Challenge yourself with our exciting word search puzzles across multiple difficulty levels. Perfect for players of all ages who want to improve vocabulary, pattern recognition, and cognitive skills while having fun!
    </div>
    
</div>

<!-- Main Title -->
<h2 class="main-title">🎯 Choose Your Level</h2>

<!-- Choose Difficulty Box -->
<div class="levels-wrapper">
    <div class="level-container">
        <h3>🌟 Easy Mode</h3>
        <div class="level-card">
            <div class="level-header">
                <span class="level-icon">🎯</span>
                <h4 class="level-title">Beginner Friendly</h4>
            </div>
            <p class="level-description">
                Perfect for beginners! Find 6 words in a 12x12 grid with simple vocabulary. Great for warming up and building confidence.
            </p>
            <form action="wordSearch.php" method="get">
                <button class="level-btn easy" name="level" value="easy">Start Easy</button>
                <input type="hidden" name="welcome" value="1">
            </form>
        </div>
    </div>

    <div class="level-container">
        <h3>⚡ Medium Mode</h3>
        <div class="level-card">
            <div class="level-header">
                <span class="level-icon">🎮</span>
                <h4 class="level-title">Balanced Challenge</h4>
            </div>
            <p class="level-description">
                The perfect balance! Find 12 words in a 16x16 grid with moderate difficulty. Ideal for regular players seeking a good challenge.
            </p>
            <form action="wordSearch.php" method="get">
                <button class="level-btn medium" name="level" value="medium">Start Medium</button>
                <input type="hidden" name="welcome" value="1">
            </form>
        </div>
    </div>

    <div class="level-container">
        <h3>🔥 Hard Mode</h3>
        <div class="level-card">
            <div class="level-header">
                <span class="level-icon">🏆</span>
                <h4 class="level-title">Expert Level</h4>
            </div>
            <p class="level-description">
                For true word masters! Find 20 words in a 20x20 grid with complex vocabulary and challenging placements. Test your limits!
            </p>
            <form action="wordSearch.php" method="get">
                <button class="level-btn hard" name="level" value="hard">Start Hard</button>
                <input type="hidden" name="welcome" value="1">
            </form>
        </div>
    </div>
</div>

<!-- Social Media Footer -->
<div class="social-footer">
    <a href="https://twitter.com" target="_blank" class="social-icon social-twitter" title="Follow us on Twitter">
        𝕏
    </a>
    <a href="https://facebook.com" target="_blank" class="social-icon social-facebook" title="Like us on Facebook">
        f
    </a>
    <a href="https://instagram.com" target="_blank" class="social-icon social-instagram" title="Follow us on Instagram">
        📷
    </a>
    <a href="https://linkedin.com" target="_blank" class="social-icon social-linkedin" title="Connect on LinkedIn">
        in
    </a>
    <a href="https://github.com" target="_blank" class="social-icon social-github" title="View on GitHub">
        ⚡
    </a>
</div>

</body>
</html>
