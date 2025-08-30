<?php
$host = "localhost"; // XAMPP/WAMP default
$user = "root";      // default MySQL user
$pass = "";          // default is empty
$dbname = "student"; // your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
