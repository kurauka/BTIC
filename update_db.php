<?php
include 'db_connect.php';

try {
  $sql = "ALTER TABLE `membership_requests` ADD COLUMN IF NOT EXISTS `admission_number` VARCHAR(50) AFTER `full_name`;";
  $conn->exec($sql);
  echo "Column 'admission_number' added successfully.";
} catch (PDOException $e) {
  echo "Error creating table: " . $e->getMessage();
}
?>