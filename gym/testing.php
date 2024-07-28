<?php
$host = 'localhost';
$dbname = 'cook_fitness';
$username = 'shvmpz';
$password = 'Pioneer.254';

// Establish a connection to the MySQL database
$pdo = new mysqli($host, $username, $password, $dbname);
if ($pdo->connect_error) {
    die("Connection failed: " . $pdo->connect_error);
}

// Function to generate a random date between two dates
function generateRandomDate($start_date, $end_date) {
    $timestamp = mt_rand(strtotime($start_date), strtotime($end_date));
    return date("Y-m-d", $timestamp);
}

// Find the user ID for the user "admin"
$user_query = "SELECT id FROM users WHERE username = 'admin'";
$result = $pdo->query($user_query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    $start_date = "2020-01-01";
    $end_date = "2023-12-31";

    for ($i = 0; $i < 10; $i++) {
        $random_date = generateRandomDate($start_date, $end_date);
        $duration = rand(30, 120); // Random duration between 30 and 120 minutes
        $calories_burned = rand(200, 1000); // Random calories burned between 200 and 1000

        $workout_insert = $pdo->prepare("INSERT INTO workouts (user_id, date, duration, calories_burned) VALUES (?, ?, ?, ?)");
        $workout_insert->bind_param("isii", $user_id, $random_date, $duration, $calories_burned);

        if ($workout_insert->execute()) {
            echo "New workout record created successfully for date: $random_date<br>";
        } else {
            echo "Error: " . $workout_insert->error . "<br>";
        }
    }
} else {
    echo "User 'admin' not found.";
}

$pdo->close();
?>
