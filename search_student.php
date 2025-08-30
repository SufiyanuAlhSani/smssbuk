<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['number'])) {
    $number = $_GET['number'];

    $sql = "SELECT * FROM studentdata WHERE number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo "<h2>Student Details</h2>";
        echo "<p><strong>Name:</strong> " . $student['name'] . "</p>";
        echo "<p><strong>Student ID:</strong> " . $student['number'] . "</p>";
        echo "<p><strong>Class:</strong> " . $student['class'] . "</p>";
        echo "<p><strong>Age:</strong> " . $student['age'] . "</p>";
        echo "<p><strong>Gender:</strong> " . $student['gender'] . "</p>";
    } else {
        echo "Student not found!";
    }
} else {
    echo "Please enter a student number!";
}

$conn->close();
?>
