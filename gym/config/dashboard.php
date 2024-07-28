<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'includes/db_connect.php';

// Fetch user data and workout statistics
$user_id = $_SESSION['user_id'];

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch user data
$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch workout statistics
$stmt = $mysqli->prepare("
    SELECT 
        COUNT(*) as workouts_completed,
        COALESCE(SUM(duration), 0) as total_duration,
        COALESCE(AVG(duration), 0) as avg_duration,
        COALESCE(SUM(calories_burned), 0) as total_calories
    FROM workouts 
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

// Calculate longest streak (this is a simplified version)
$stmt = $mysqli->prepare("
    SELECT COUNT(DISTINCT date) as longest_streak
    FROM workouts
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$streak = $result->fetch_assoc();
$stats['longest_streak'] = $streak['longest_streak'];

// Fetch monthly check-ins
$stmt = $mysqli->prepare("
    SELECT DATE(date) as date, COUNT(*) as count
    FROM workouts
    WHERE user_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    GROUP BY DATE(date)
    ORDER BY date
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$monthly_checkins = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();

// Include the view
include 'dashboard_view.php';
?>