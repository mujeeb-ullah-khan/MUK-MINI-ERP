<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // Always redirect to the central login page
  header("Location: ../auth/login.php");
  exit;
}
?>
