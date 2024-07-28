<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'config/db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
    $gender = $_POST['gender'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    if ($date_of_birth === null) {
        $stmt = $mysqli->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, gender = ?, height = ? WHERE id = ?");
        $stmt->bind_param("ssssdi", $first_name, $last_name, $email, $gender, $height, $user_id);
    } else {
        $stmt = $mysqli->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, date_of_birth = ?, gender = ?, height = ? WHERE id = ?");
        $stmt->bind_param("sssssdi", $first_name, $last_name, $email, $date_of_birth, $gender, $height, $user_id);
    }
    $stmt->execute();

    // Update or insert body measurement
    $stmt = $mysqli->prepare("INSERT INTO body_measurements (user_id, date, weight) VALUES (?, CURDATE(), ?) ON DUPLICATE KEY UPDATE weight = ?");
    $stmt->bind_param("idd", $user_id, $weight, $weight);
    $stmt->execute();

    // Redirect to refresh the page with updated data
    header("Location: settings_view.php");
    exit();
}

// Fetch latest body measurement
$stmt = $mysqli->prepare("SELECT weight FROM body_measurements WHERE user_id = ? ORDER BY date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$latest_measurement = $result->fetch_assoc();
if ($latest_measurement === null) {
    $latest_measurement = ['weight' => null];
}
// Calculate BMI
function calculateBMI($weight, $height) {
    if ($weight && $height) {
        $height_m = $height / 100; // Convert cm to m
        return round($weight / ($height_m * $height_m), 1);
    }
    return null;
}

$bmi = calculateBMI($latest_measurement['weight'], $user['height']);

// Fetch user's goals
$stmt = $mysqli->prepare("SELECT * FROM goals WHERE user_id = ? AND achieved = 0 ORDER BY deadline");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$goals = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();

// Include the view
?>
