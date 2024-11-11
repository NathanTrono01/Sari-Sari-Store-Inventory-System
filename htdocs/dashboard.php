<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include your database connection
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* Simple CSS for navigation bar */
        nav {
            background-color: #333;
            overflow: hidden;
        }
        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            float: left;
        }
        nav a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Sari-Sari Store Dashboard</h1>

    <!-- Navigation Bar -->
    <nav>
        <?php if ($_SESSION['role'] == 'Admin'): ?>
            <a href="inventory.php">View Inventory</a>
            <a href="sales.php">View Sales</a>
            <a href="manage_inventory.php">Manage Inventory</a>
            <a href="user_management.php">User Management</a>
        <?php elseif ($_SESSION['role'] == 'Employee'): ?>
            <a href="view_products.php">View Products</a>
            <a href="record_sale.php">Record Sale</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>

    <p>Welcome, <?= htmlspecialchars($_SESSION['role']); ?>! You are logged in.</p>

</body>
</html>
