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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d');
    $meal_type = $_POST['meal_type'];
    $food_item = $_POST['food_item'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbohydrates = $_POST['carbohydrates'];
    $fats = $_POST['fats'];

    $stmt = $mysqli->prepare("
        INSERT INTO nutrition_logs (user_id, date, meal_type, food_item, calories, protein, carbohydrates, fats)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isssiddd", $user_id, $date, $meal_type, $food_item, $calories, $protein, $carbohydrates, $fats);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Food item added successfully!";
    } else {
        $_SESSION['error_message'] = "Error adding food item. Please try again.";
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../nutrition_tracker_view.php");
    exit();
} else {
    header("Location: ../nutrition_tracker_view.php");
    exit();
}
?>