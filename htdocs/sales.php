<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
} else {
    $role = ''; // Set role to empty if not logged in
}

// Get sales data
$sql = "SELECT * FROM sales";
$result = mysqli_query($conn, $sql);
include 'navbar.php';
?>

<h1>Sales Transactions</h1>

<!-- Sales Table -->
<table border="1">
    <tr>
        <th>Sale ID</th>
        <th>Item Name</th>
        <th>Quantity Sold</th>
        <th>Total Price</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['sale_id']; ?></td>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['quantity_sold']; ?></td>
            <td><?php echo $row['total_price']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>