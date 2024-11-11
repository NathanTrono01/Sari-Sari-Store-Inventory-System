<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Add new item to inventory
if (isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "INSERT INTO inventory (item_name, quantity, price) VALUES ('$item_name', '$quantity', '$price')";
    if (mysqli_query($conn, $sql)) {
        echo "Item added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit or delete item
if (isset($_GET['action']) && isset($_GET['item_id'])) {
    $action = $_GET['action'];
    $item_id = $_GET['item_id'];

    if ($action == 'delete') {
        // Delete item
        $sql = "DELETE FROM inventory WHERE item_id = '$item_id'";
        if (mysqli_query($conn, $sql)) {
            echo "Item deleted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Get inventory items
$sql = "SELECT * FROM inventory";
$result = mysqli_query($conn, $sql);
?>

<h1>Inventory Management</h1>

<!-- Add Item Form -->
<h2>Add New Item</h2>
<form method="POST">
    <label>Item Name:</label>
    <input type="text" name="item_name" required>
    <label>Quantity:</label>
    <input type="number" name="quantity" required>
    <label>Price:</label>
    <input type="text" name="price" required>
    <input type="submit" name="add_item" value="Add Item">
</form>

<!-- Inventory Table -->
<h2>Inventory List</h2>
<table border="1">
    <tr>
        <th>Item ID</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <!-- Edit and Delete Buttons -->
                <a href="inventory.php?action=delete&item_id=<?php echo $row['item_id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
