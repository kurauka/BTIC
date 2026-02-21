<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../db_connect.php';

header("Content-Disposition: attachment; filename=members_export_" . date('Y-m-d') . ".csv");
header("Content-Type: text/csv");

$output = fopen("php://output", "w");

// Column Headers
fputcsv($output, array('ID', 'Full Name', 'Admission Number', 'Email', 'Phone', 'Institution', 'Course', 'Year of Study', 'Interests', 'Status', 'Date Joined'));

// Fetch Data
$query = "SELECT id, full_name, admission_number, email, phone, institution, course, year_of_study, interests, status, created_at FROM membership_requests ORDER BY created_at DESC";
$stmt = $conn->query($query);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
