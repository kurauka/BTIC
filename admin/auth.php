<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=All fields are required");
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username AND role = 'admin'");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Success
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            // Invalid credentials
            header("Location: login.php?error=Invalid username or password");
            exit;
        }
    } catch (PDOException $e) {
        // Show specific error for debugging
        header("Location: login.php?error=DB Error: " . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>