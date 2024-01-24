<?php

$hostname = "127.0.0.1";
$username = "root";
$password = "";
$db_name = "22145775_21196190_22220193";

$mysqli = new mysqli($hostname, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
