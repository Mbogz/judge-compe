<?php
$mysqli = new mysqli("localhost", "judging_user", "judging_pass", "judging_system");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "Connection successful!";
?>
