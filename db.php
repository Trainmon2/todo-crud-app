<?php
$host = "localhost";
$user = "root"; // default username for XAMPP
$pass = "";     // password is empty by default
$dbname = "todo_app";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>