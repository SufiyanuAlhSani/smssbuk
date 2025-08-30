<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.history.back();</script>";
        exit;
    }

    // Check if username or email exists
    $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists'); window.history.back();</script>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into DB
    $sql = "INSERT INTO user (fullname, username, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully! Redirecting to login...'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('Error creating account'); window.history.back();</script>";
    }
}
?>
