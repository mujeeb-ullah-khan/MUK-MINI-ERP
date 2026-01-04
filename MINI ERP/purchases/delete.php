<?php
session_start();
require "../auth/auth_check.php";
require "../config/db.php";

if (isset($_GET['id'])) {
    $purchase_id = (int)$_GET['id'];
    
    // Get purchase info
    $stmt = $conn->prepare("SELECT product_id, quantity FROM purchases WHERE id=?");
    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $purchase = $result->fetch_assoc();
    
    if ($purchase) {
        // CHANGED: Update stock_quantity (subtract because we're deleting a purchase)
        $update = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id=?");
        $update->bind_param("ii", $purchase['quantity'], $purchase['product_id']);
        
        if ($update->execute()) {
            // Delete purchase record
            $delete = $conn->prepare("DELETE FROM purchases WHERE id=?");
            $delete->bind_param("i", $purchase_id);
            
            if ($delete->execute()) {
                $_SESSION['success'] = "Purchase deleted and stock updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete purchase record.";
            }
            $delete->close();
        } else {
            $_SESSION['error'] = "Failed to update stock.";
        }
        $update->close();
    } else {
        $_SESSION['error'] = "Purchase record not found!";
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid purchase ID!";
}

header("Location: index.php");
exit;
?>