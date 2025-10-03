<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Get old quantity
    $old_quantity = $product['quantity'];
    $diff = $quantity - $old_quantity;

    // Update product
    $conn->query("UPDATE products SET name='$name', quantity=$quantity, price=$price, category='$category' WHERE id=$id");

    // Insert stock change (if quantity changed)
    if ($diff != 0) {
        $type = $diff > 0 ? 'IN' : 'OUT';
        $conn->query("INSERT INTO stock_history (product_id, type, quantity) VALUES ($id, '$type', " . abs($diff) . ")");
    }

    $_SESSION['success'] = "Product updated successfully!";
    header("Location: view-product.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>


<header>
    <h1>Inventory Management System</h1>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="view-product.php">View Products</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="form-card">
    <h2>✏️ Update Product</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= $product['name'] ?>" required />
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required />
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required />
        <select name="category" required>
            <option <?= $product['category'] == 'Electronics' ? 'selected' : '' ?>>Electronics</option>
            <option <?= $product['category'] == 'Groceries' ? 'selected' : '' ?>>Groceries</option>
            <option <?= $product['category'] == 'Clothing' ? 'selected' : '' ?>>Clothing</option>
            <option <?= $product['category'] == 'Office' ? 'selected' : '' ?>>Office</option>
        </select>
        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
