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

// Fetch data
$sql = "SELECT id, name, class, age, gender FROM studentdata";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #004080;
            color: white;
        }
    </style>
</head>
<body>
<h2 style="text-align:center;">Student Records</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Class</th>
        <th>Age</th>
        <th>Gender</th>
    </tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["name"]."</td>
                <td>".$row["class"]."</td>
                <td>".$row["age"]."</td>
                <td>".$row["gender"]."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No students found</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>
