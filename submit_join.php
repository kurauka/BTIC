<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $institution = htmlspecialchars(trim($_POST['institution']));
    $course = htmlspecialchars(trim($_POST['course']));
    $year_of_study = htmlspecialchars(trim($_POST['year_of_study']));
    $interests = htmlspecialchars(trim($_POST['interests']));

    if (empty($full_name) || empty($email) || empty($phone)) {
        header("Location: index.php?status=error&message=Required fields missing");
        exit;
    }

    try {
        $sql = "INSERT INTO membership_requests (full_name, email, phone, institution, course, year_of_study, interests) 
                VALUES (:full_name, :email, :phone, :institution, :course, :year_of_study, :interests)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':full_name' => $full_name,
            ':email' => $email,
            ':phone' => $phone,
            ':institution' => $institution,
            ':course' => $course,
            ':year_of_study' => $year_of_study,
            ':interests' => $interests
        ]);

        header("Location: index.php?status=success&message=Application submitted successfully!");
        exit;

    } catch (PDOException $e) {
        // error_log($e->getMessage());
        header("Location: index.php?status=error&message=Application failed. Try again.");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>