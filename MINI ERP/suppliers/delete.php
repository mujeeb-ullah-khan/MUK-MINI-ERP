<?php
session_start();
require "../auth/auth_check.php";
require "../config/db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid supplier ID.";
    header("Location: index.php");
    exit();
}

$supplier_id = (int)$_GET['id'];

// Check if supplier exists
$check = $conn->prepare("SELECT id, name FROM suppliers WHERE id = ?");
$check->bind_param("i", $supplier_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $_SESSION['error'] = "Supplier not found.";
    header("Location: index.php");
    exit();
}

$check->bind_result($id, $name);
$check->fetch();
$check->close();

// Check dependencies
$products_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE supplier_id = $supplier_id")->fetch_assoc()['count'];
$purchases_count = $conn->query("SELECT COUNT(*) as count FROM purchases WHERE supplier_id = $supplier_id")->fetch_assoc()['count'];

if ($products_count > 0 || $purchases_count > 0) {
    $message = "Cannot delete supplier '$name'. ";
    if ($products_count > 0) $message .= "Has $products_count product(s). ";
    if ($purchases_count > 0) $message .= "Has $purchases_count purchase(s). ";
    $message .= "Please remove or reassign first.";
    
    $_SESSION['error'] = $message;
    header("Location: index.php");
    exit();
}

// Delete supplier
$stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
$stmt->bind_param("i", $supplier_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Supplier '$name' deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete supplier: " . $conn->error;
}

$stmt->close();
$conn->close();

header("Location: index.php");
exit();
?>