<?php
include 'db_connect.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `membership_requests` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `full_name` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `phone` varchar(20) DEFAULT NULL,
      `institution` varchar(100) DEFAULT NULL,
      `course` varchar(100) DEFAULT NULL,
      `year_of_study` varchar(20) DEFAULT NULL,
      `interests` text DEFAULT NULL,
      `status` enum('pending','approved','rejected') DEFAULT 'pending',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $conn->exec($sql);
    echo "Table 'membership_requests' created successfully (or already exists).";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>