<?php
require "../auth/auth_check.php";
require "../config/db.php";

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $quantity = (int)$_POST['stock_quantity'];
  $reorder = (int)$_POST['reorder_level'];
  $price = (float)$_POST['price'];

  $stmt = $conn->prepare("UPDATE products SET name=?,category=?,stock_quantity=?,reorder_level=?,price=? WHERE id=?");
  $stmt->bind_param("ssiddi",$name,$category,$quantity,$reorder,$price,$id);
  $stmt->execute();
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Edit Product</h2>
<form method="post">
  Name: <input name="name" value="<?= $product['name'] ?>"><br>
  Category: <input name="category" value="<?= $product['category'] ?>"><br>
  Quantity: <input type="number" name="stock_quantity" value="<?= $product['stock_quantity'] ?>"><br>
  Reorder Level: <input type="number" name="reorder_level" value="<?= $product['reorder_level'] ?>"><br>
  Price: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"><br><br>
  <button type="submit">Update</button>
</form>
</body>
</html>
