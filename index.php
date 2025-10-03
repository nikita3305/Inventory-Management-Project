<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Inventory Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .dashboard {
      max-width: 600px;
      margin: 60px auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      text-align: center;
      animation: fadeIn 1s ease;
    }

    .dashboard h2 {
      font-size: 28px;
      color: #00bcd4;
      margin-bottom: 10px;
    }

    .dashboard p {
      font-size: 18px;
      color: #555;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
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
      <a href="add-product.php">Add Product</a>
      <a href="view-product.php">View Products</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main>
    <section class="dashboard">
      <h2>👋 Welcome, Admin!</h2>
      <p>Manage your inventory with ease.<br>Use the navigation menu to get started.</p>
    </section>
  </main>
</body>
</html>
