<?php
$mysqli = new mysqli("localhost", "root", "", "db_ci4");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$mysqli->query("UPDATE user SET role = 'guest' WHERE username = 'raff'");
echo "Role updated for raff!";
