<?php
$host     = "172.16.20.121"; // Database Host
$user     = "hrportal"; // Database Username
$password = "hrp0rt4lAPPS"; // Database's user Password
$database = "unpak_hr"; // Database Name

$mysqli = new mysqli($host, $user, $password, $database);

// Checking Connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8mb4");

// Settings
include "config_settings.php";
?>