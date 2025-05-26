<?php
$mysqli = new mysqli("localhost", "judging_user", "judging_pass", "judging_system");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8mb4 for full Unicode support
if (!$mysqli->set_charset("utf8mb4")) {
    die("Error loading character set utf8mb4: " . $mysqli->error);
}
?>
