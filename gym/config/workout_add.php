<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];
    $notes = $_POST['notes'];

    $stmt = $mysqli->prepare("INSERT INTO workouts (user_id, date, duration, calories_burned, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $user_id, $date, $duration, $calories_burned, $notes);

    if ($stmt->execute()) {
        header("Location: ../dashboard_view.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$mysqli->close();
?>
