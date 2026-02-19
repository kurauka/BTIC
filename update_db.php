<?php
include 'db_connect.php';

try {
  // 1. Create membership_requests table if not exists
  $sql = "CREATE TABLE IF NOT EXISTS `membership_requests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `full_name` varchar(100) NOT NULL,
    `admission_number` varchar(50) DEFAULT NULL,
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
  echo "Table 'membership_requests' ensured successfully.<br>";

  // 2. Ensure admission_number column exists (Standard MySQL compatible way)
  try {
    $sql = "ALTER TABLE `membership_requests` ADD COLUMN `admission_number` VARCHAR(50) AFTER `full_name`;";
    $conn->exec($sql);
    echo "Column 'admission_number' added successfully.<br>";
  } catch (PDOException $e) {
    // Ignore "Duplicate column name" error (1060)
    if ($e->errorInfo[1] == 1060) {
      echo "Column 'admission_number' already exists.<br>";
    } else {
      throw $e;
    }
  }

} catch (PDOException $e) {
  echo "Error updating database: " . $e->getMessage();
}
?>