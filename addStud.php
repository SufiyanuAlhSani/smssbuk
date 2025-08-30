<?php
// Database connection details
$servername = "localhost";
$username = "root";  // default for XAMPP
$password = "";      // leave empty for default
$dbname = "student"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$class = $_POST['class'];
$age = $_POST['age'];
$gender = $_POST['gender'];

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO studentdata (name, class, age, gender) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $name, $class, $age, $gender);

// Execute and check
if ($stmt->execute()) {
    echo "<h3>Student added successfully!</h3>";
    echo "<a href='addStudentForm.php'>Go Back</a>"; // Change to your form file name
} else {
    echo "Error: " . $stmt->error;
}

// Close
$stmt->close();
$conn->close();
?>
