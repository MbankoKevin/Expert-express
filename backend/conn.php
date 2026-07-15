<?php
$host = "localhost";
$user = "root";
$password = ""; // Put your password inside these quotes
$database = "track";

$link = mysqli_connect($host, $user, $password, $database);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>