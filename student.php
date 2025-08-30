<?php
$conn = new mysqli("localhost", "root", "", "student");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM studentdata WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) { die("Student not found"); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Student</title>
    <style>
        body {font-family: Arial; text-align: center;}
        .btn {padding: 10px 20px; margin: 10px; display: inline-block; border: none; cursor: pointer;}
        .update {background: #28a745; color: white;}
        .delete {background: #dc3545; color: white;}
    </style>
</head>
<body>
<h2>Student: <?php echo $student['name']; ?></h2>
<p><b>Class:</b> <?php echo $student['class']; ?> | 
<b>Age:</b> <?php echo $student['age']; ?> | 
<b>Gender:</b> <?php echo $student['gender']; ?></p>

<a href="update.php?id=<?php echo $id; ?>" class="btn update">Update Student</a>
<a href="delete.php?id=<?php echo $id; ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete Student</a>
</body>
</html>
