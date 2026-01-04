<?php
require "../auth/auth_check.php";
require "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Delete customer directly
$stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?msg=customer_deleted");
    exit;
} else {
    die("Failed to delete customer: " . $conn->error);
}
