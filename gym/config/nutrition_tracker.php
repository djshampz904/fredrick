<?php
session_start();

// Set session cookie parameters
$lifetime = 3600; // 1 hour
$path = '/'; // Available in whole domain
$domain = $_SERVER['HTTP_HOST'];
$secure = isset($_SERVER['HTTPS']);
$httponly = true;

session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

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

// Fetch nutrition logs for the last 7 days
$stmt = $mysqli->prepare("
    SELECT date, SUM(calories) as total_calories, SUM(protein) as total_protein, 
           SUM(carbohydrates) as total_carbs, SUM(fats) as total_fats
    FROM nutrition_logs
    WHERE user_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY date
    ORDER BY date
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$nutrition_logs = $result->fetch_all(MYSQLI_ASSOC);

// Calculate average daily intake
$stmt = $mysqli->prepare("
    SELECT AVG(calories) as avg_calories, AVG(protein) as avg_protein, 
           AVG(carbohydrates) as avg_carbs, AVG(fats) as avg_fats
    FROM (
        SELECT date, SUM(calories) as calories, SUM(protein) as protein, 
               SUM(carbohydrates) as carbohydrates, SUM(fats) as fats
        FROM nutrition_logs
        WHERE user_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY date
    ) as daily_totals
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$avg_intake = $result->fetch_assoc();

$mysqli->close();

// Include the view
include 'nutrition_tracker_view.php';
?>