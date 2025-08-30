<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete record if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $deleteSql = "DELETE FROM studentdata WHERE id = $id";
    if ($conn->query($deleteSql) === TRUE) {
        echo "<p style='color:green;'>Record deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error deleting record: " . $conn->error . "</p>";
    }
}

// Fetch all students
$sql = "SELECT id, name, number, class, age, gender FROM studentdata";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #004080; color: white; }
        a.delete { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Delete Student Records</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Number</th>
            <th>Class</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['number']}</td>
                        <td>{$row['class']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['gender']}</td>
                        <td><a class='delete' href='deleteStudent.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No students found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
