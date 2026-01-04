<?php
session_start();
require "../auth/auth_check.php";
require "../config/db.php";

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // First check if product exists
    $check = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        // Check if product has sales or purchases
        $check_sales = $conn->query("SELECT COUNT(*) as count FROM sales WHERE product_id = $id");
        $sales_count = $check_sales->fetch_assoc()['count'];
        
        $check_purchases = $conn->query("SELECT COUNT(*) as count FROM purchases WHERE product_id = $id");
        $purchases_count = $check_purchases->fetch_assoc()['count'];
        
        if ($sales_count > 0 || $purchases_count > 0) {
            $_SESSION['error'] = "Cannot delete product. It has $sales_count sales and $purchases_count purchases associated.";
        } else {
            // Delete the product
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Product deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete product: " . $conn->error;
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error'] = "Product not found!";
    }
    $check->close();
} else {
    $_SESSION['error'] = "Invalid product ID!";
}

header("Location: index.php");
exit;
?>