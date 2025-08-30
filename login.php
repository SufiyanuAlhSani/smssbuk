<?php
session_start();
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate fields
    if (empty($username) || empty($password)) {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit;
    }

    // Fetch user by username
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Correct password -> create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.history.back();</script>";
    }
}
?>
