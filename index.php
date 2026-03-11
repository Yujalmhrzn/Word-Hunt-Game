<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Word Search Game</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css" />

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css?family=Itim&display=swap"
      rel="stylesheet"
    />

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="WordSearch/wordSearchGame.js"></script>
  </head>

  <body>
    <div id="start-screen">
      <main class="Welcome-Background">

        <section class="creator">
          <script>
            function creator() {
              window.location.href = "Profile.html";
            }
          </script>
        </section>

        <!-- title-start REMOVED -->

        <section class="game-selection">
          <p>Click Below To Play</p>
        </section>

        <section class="play">
          <button id="wordSearch-play" onclick="wordSearch()">
            <span class="title">Word Hunt</span>
          </button>

          <script>
            function wordSearch() {
              window.location.href = "login.php?msg=Please login to play.";
            }
          </script>
        </section>

      </main>
    </div>
  </body>
</html>
