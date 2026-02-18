<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $interest_type = htmlspecialchars(trim($_POST['interest_type']));
    $message_body = htmlspecialchars(trim($_POST['message_body']));

    // Basic validation
    if (empty($first_name) || empty($email) || empty($message_body)) {
        header("Location: index.php?status=error&message=All fields are required");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error&message=Invalid email format");
        exit;
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO messages (first_name, last_name, email, interest_type, message_body) 
                VALUES (:first_name, :last_name, :email, :interest_type, :message_body)";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':interest_type', $interest_type);
        $stmt->bindParam(':message_body', $message_body);

        // Execute
        $stmt->execute();

        // Redirect with success message
        header("Location: index.php?status=success&message=Message sent successfully!");
        exit;

    } catch (PDOException $e) {
        // Log error (in a real app) and redirect
        // error_log($e->getMessage());
        header("Location: index.php?status=error&message=Unable to send message. Please try again later.");
        exit;
    }
} else {
    // Not a POST request
    header("Location: index.php");
    exit;
}
?>