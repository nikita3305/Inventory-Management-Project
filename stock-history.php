<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
$result = $conn->query("SELECT s.*, p.name FROM stock_history s JOIN products p ON s.product_id = p.id ORDER BY s.timestamp DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock History</title>
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
    <h1>Stock In/Out History</h1>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="view-product.php">View Products</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<table>
    <tr><th>Product</th><th>Type</th><th>Qty</th><th>Date</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['type'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['timestamp'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
