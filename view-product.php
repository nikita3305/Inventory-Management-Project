<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Get only non-deleted products
$result = $conn->query("SELECT * FROM products WHERE deleted = 0 ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .low-stock { color: red; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h1>Inventory Management System</h1>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="add-product.php">Add Product</a>
        <a href="stock-history.php">Stock History</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<!-- Alert messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Search bar -->
<input type="text" id="searchInput" placeholder="Search by name or category..." onkeyup="filterTable()" style="margin: 20px auto; display: block; padding: 10px; width: 80%; max-width: 400px;" />

<!-- Products Table -->
<table>
    <tr>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Category</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>

    <?php
    $sr = 1;
    while($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $sr++; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['category']; ?></td>
        <td <?= $row['quantity'] < 5 ? 'class="low-stock"' : '' ?>>
            <?= $row['quantity']; ?>
        </td>
        <td>₹<?= $row['price']; ?></td>
        <td>
            <a class="action-btn" href="update-product.php?id=<?= $row['id'] ?>">Edit</a>
            <a class="action-btn" href="delete-product.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- Search filter script -->
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("table tr:not(:first-child)");
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>

</body>
</html>
