<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "You must be logged in.";
    exit();
}

$username = $_SESSION['username'];
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;

$conn = new mysqli("localhost", "root", "", "login_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert a new score (no update logic — store all plays)
$sql = "INSERT INTO leaderboard (username, score) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $username, $score);

header('Content-Type: application/json');

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Score submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit score.']);
}

$stmt->close();
$conn->close();
?>
