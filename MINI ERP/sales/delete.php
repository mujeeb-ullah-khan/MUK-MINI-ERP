<?php
session_start();
require "../auth/auth_check.php";
require "../config/db.php";

if (isset($_GET['id'])) {
    $sale_id = (int)$_GET['id'];
    
    // Get sale info
    $stmt = $conn->prepare("SELECT product_id, quantity FROM sales WHERE id=?");
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sale = $result->fetch_assoc();
    
    if ($sale) {
        // Restore stock using stock_quantity column
        $update = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id=?");
        $update->bind_param("ii", $sale['quantity'], $sale['product_id']);
        
        if ($update->execute()) {
            // Delete sale record
            $delete = $conn->prepare("DELETE FROM sales WHERE id=?");
            $delete->bind_param("i", $sale_id);
            
            if ($delete->execute()) {
                $_SESSION['success'] = "Sale deleted and stock restored successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete sale record.";
            }
            $delete->close();
        } else {
            $_SESSION['error'] = "Failed to restore stock.";
        }
        $update->close();
    } else {
        $_SESSION['error'] = "Sale record not found!";
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid sale ID!";
}

header("Location: index.php");
exit;
?>