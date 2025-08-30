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

$name = "";
$result = null;

if (isset($_POST['search'])) {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $sql = "SELECT id, name, class, age, gender FROM studentdata WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $name . "%"; // Add wildcards for partial match
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Student</title>
    <style>
        body {font-family: Arial, sans-serif; background: #f4f4f4; text-align: center;}
        form {margin: 20px auto;}
        input[type="text"] {
            padding: 10px; width: 250px; font-size: 14px;
        }
        button {
            padding: 10px 20px; background: #004080; color: white; border: none; cursor: pointer;
        }
        button:hover {background: #003366;}
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

<h2>Search Student by Name</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Enter student name" required>
    <button type="submit" name="search">Search</button>
</form>

<?php if ($result): ?>
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
            echo "<tr><td colspan='4'>No student found</td></tr>";
        }
        ?>
    </table>
<?php endif; ?>

</body>
</html>
