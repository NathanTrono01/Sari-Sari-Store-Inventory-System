<?php
// Include database connection
include('db.php');
include 'navbar.php';

// SQL query to get total sales by item
$sql = "SELECT item_id, SUM(quantity) as total_sold FROM sales GROUP BY item_id ORDER BY total_sold DESC";
$result = mysqli_query($conn, $sql);

// Display analytics in a table format
echo "<table border='1'>
        <tr>
            <th>Item Name</th>
            <th>Total Sold</th>
            <th>Earnings</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $itemId = $row['item_id'];
    $totalSold = $row['total_sold'];

    // Get item details (name, price)
    $itemSql = "SELECT name, price FROM inventory WHERE id='$itemId'";
    $itemResult = mysqli_query($conn, $itemSql);
    $item = mysqli_fetch_assoc($itemResult);

    // Calculate earnings
    $earnings = $totalSold * $item['price'];

    // Display row in the table
    echo "<tr>
            <td>" . $item['name'] . "</td>
            <td>" . $totalSold . "</td>
            <td>" . number_format($earnings, 2) . "</td>
        </tr>";
}

// Close the table
echo "</table>";
?>
