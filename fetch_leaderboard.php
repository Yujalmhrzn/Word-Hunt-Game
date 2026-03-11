<?php
session_start();
// ... (rest of your existing PHP code) ...

$conn = new mysqli("localhost", "root", "", "login_system");

if ($conn->connect_error) {
    error_log("Leaderboard DB Connection failed: " . $conn->connect_error);
    echo '<li>Error loading leaderboard. Please try again later.</li>';
    exit();
}

// Fetch all scores, ordered by score (lowest first), limited to 10 entries
// This will show multiple entries for the same user if they have multiple scores
$sql = "SELECT username, score
        FROM leaderboard
        ORDER BY score ASC
        LIMIT 10"; // You might want to adjust this LIMIT based on how many entries you want to show

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $rank = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<li data-rank="' . $rank . '">';
            echo '<span class="leaderboard-username">' . htmlspecialchars($row['username']) . '</span>';
            echo '<span class="leaderboard-score">' . htmlspecialchars($row['score']) . 's</span>';
            echo '</li>';
            $rank++;
        }
    } else {
        echo '<li>No scores yet!</li>';
    }
} else {
    error_log("Leaderboard Query failed: " . $conn->error);
    echo '<li>Failed to retrieve scores.</li>';
}

$conn->close();
?>