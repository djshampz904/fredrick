<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user's nutrition logs for today
$today = date('Y-m-d');
$stmt = $mysqli->prepare("
    SELECT * FROM nutrition_logs
    WHERE user_id = ? AND date = ?
    ORDER BY meal_type
");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();
$nutrition_logs = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total macros for today
$total_calories = 0;
$total_protein = 0;
$total_carbs = 0;
$total_fats = 0;

foreach ($nutrition_logs as $log) {
    $total_calories += $log['calories'];
    $total_protein += $log['protein'];
    $total_carbs += $log['carbohydrates'];
    $total_fats += $log['fats'];
}

// Set default goals (you can adjust these values or implement a way for users to set their own goals)
$user_goals = [
    'calorie_goal' => 2000,
    'protein_goal' => 150,
    'carb_goal' => 250,
    'fat_goal' => 65
];

$mysqli->close();
?>