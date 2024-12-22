<?php
session_start(); // Start a session to store user information
include('conn/conn.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare a statement to fetch the user's hashed password from the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password matches, login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: admin.php"); // Redirect to admin dashboard
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password!'); window.location.href='index.php';</script>";
    }
}
?>
