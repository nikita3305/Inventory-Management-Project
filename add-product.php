<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Check for reusable ID from deleted products
    $reusable = $conn->query("SELECT id FROM products WHERE deleted = 1 ORDER BY id ASC LIMIT 1");

    if ($reusable->num_rows > 0) {
        // Reuse deleted product row
        $reuse_id = $reusable->fetch_assoc()['id'];
        $conn->query("UPDATE products SET name='$name', quantity=$quantity, price=$price, category='$category', deleted=0 WHERE id=$reuse_id");
        $conn->query("INSERT INTO stock_history (product_id, type, quantity) VALUES ($reuse_id, 'IN', $quantity)");
        $_SESSION['success'] = "Product added by reusing ID #$reuse_id.";
    } else {
        // Insert new product normally
        $conn->query("INSERT INTO products (name, quantity, price, category) VALUES ('$name', $quantity, $price, '$category')");
        $product_id = $conn->insert_id;
        $conn->query("INSERT INTO stock_history (product_id, type, quantity) VALUES ($product_id, 'IN', $quantity)");
        $_SESSION['success'] = "New product added successfully.";
    }

    header("Location: view-product.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header>
    <h1>Inventory Management System</h1>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="view-product.php">View Products</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="form-card">
    <h2>➕ Add Product</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required />
        <input type="number" name="quantity" placeholder="Quantity" required />
        <input type="number" step="0.01" name="price" placeholder="Price" required />
        <select name="category" required>
            <option value="Electronics">Electronics</option>
            <option value="Groceries">Groceries</option>
            <option value="Clothing">Clothing</option>
            <option value="Office">Office Supplies</option>
        </select>
        <button type="submit">Add Product</button>
    </form>
</div>
</body>
</html>
