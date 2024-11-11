<?php
$sql = "SELECT item_id, SUM(quantity) as total_sold FROM sales GROUP BY item_id ORDER BY total_sold DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $itemId = $row['item_id'];
    $totalSold = $row['total_sold'];

    // Get item details
    $itemSql = "SELECT name, price FROM inventory WHERE id='$itemId'";
    $itemResult = mysqli_query($conn, $itemSql);
    $item = mysqli_fetch_assoc($itemResult);

    echo "Item: " . $item['name'] . " - Total Sold: " . $totalSold . " - Earnings: " . ($totalSold * $item['price']) . "<br>";
}