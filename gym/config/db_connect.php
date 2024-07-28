<?php
$host = 'localhost';
$dbname = 'cook_fitness';
$username = 'shvmpz';
$password = 'Pioneer.254';

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
