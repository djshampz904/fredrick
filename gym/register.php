<?php
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }
}
?>