<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Get product quantity for logging
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if ($product && !$product['deleted']) {
    // Log full stock out
    if ($product['quantity'] > 0) {
        $conn->query("INSERT INTO stock_history (product_id, type, quantity) VALUES ($id, 'OUT', {$product['quantity']})");
    }

    // Soft delete
    $conn->query("UPDATE products SET deleted = 1 WHERE id = $id");

    $_SESSION['success'] = "Product marked as deleted. Stock history retained.";
} else {
    $_SESSION['error'] = "Product not found or already deleted.";
}

header("Location: view-product.php");
exit();
?>
