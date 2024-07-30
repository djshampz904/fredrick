<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exercise_id = isset($_POST['exercise_id']) && $_POST['exercise_id'] !== '' ? $_POST['exercise_id'] : null;
    $goal_type = $_POST['goal_type'];
    $target_value = $_POST['target_value'];
    $current_value = $_POST['current_value'];
    $deadline = $_POST['deadline'];

    $stmt = $mysqli->prepare("INSERT INTO goals (user_id, exercise_id, goal_type, target_value, current_value, deadline) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdds", $user_id, $exercise_id, $goal_type, $target_value, $current_value, $deadline);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Goal added successfully!";
    } else {
        $_SESSION['error_message'] = "Error adding goal. Please try again.";
    }

    $mysqli->close();

    header("Location: ../settings_view.php");
    exit();
} else {
    header("Location: ../settings_view.php");
    exit();
}
?>