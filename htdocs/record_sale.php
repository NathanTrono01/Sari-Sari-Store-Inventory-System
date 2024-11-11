<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get all inventory items
$sql = "SELECT * FROM inventory";
$result = mysqli_query($conn, $sql);

// Record a sale
if (isset($_POST['record_sale'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Fetch the current inventory quantity and price of the item
    $item_sql = "SELECT price, quantity FROM inventory WHERE item_id = '$item_id'";
    $item_result = mysqli_query($conn, $item_sql);
    $item = mysqli_fetch_assoc($item_result);
    $price = $item['price'];
    $current_quantity = $item['quantity'];

    // Check if there is enough stock
    if ($quantity > $current_quantity) {
        echo "Not enough stock available.";
    } else {
        // Calculate total price
        $total_price = $price * $quantity;

        // Insert sale into sales table
        $sale_sql = "INSERT INTO sales (item_id, quantity, total_price) VALUES ('$item_id', '$quantity', '$total_price')";
        if (mysqli_query($conn, $sale_sql)) {
            // Update inventory by subtracting the sold quantity
            $new_quantity = $current_quantity - $quantity;
            $update_sql = "UPDATE inventory SET quantity = '$new_quantity' WHERE item_id = '$item_id'";

            if (mysqli_query($conn, $update_sql)) {
                echo "Sale recorded successfully! Inventory updated.";
            } else {
                echo "Error updating inventory: " . mysqli_error($conn);
            }
        } else {
            echo "Error recording sale: " . mysqli_error($conn);
        }
    }
}

include 'navbar.php';
?>

<h1>Record a Sale</h1>
<link rel="stylesheet" href="style.css">
<form method="POST">
    <label>Item:</label>
    <select name="item_id" required>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['item_name']; ?></option>
        <?php endwhile; ?>
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" required>

    <input type="submit" name="record_sale" value="Record Sale">
</form>
