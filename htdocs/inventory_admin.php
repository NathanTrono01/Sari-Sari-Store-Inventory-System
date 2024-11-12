<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Delete item
if (isset($_GET['action']) && isset($_GET['item_id'])) {
    $action = $_GET['action'];
    $item_id = $_GET['item_id'];

    if ($action == 'delete') {
        // Delete item
        $sql = "DELETE FROM inventory WHERE item_id = '$item_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['alert'] = "Item deleted successfully!";
        } else {
            $_SESSION['alert'] = "Error: " . mysqli_error($conn);
        }
        header("Location: inventory.php");
        exit(); // Stop further execution
    }
}

// Get inventory items
$sql = "SELECT * FROM inventory";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .back a[href*="admin_dashboard.php"]:hover {
            background-color: #FB667A;
            cursor: pointer;
            box-shadow: 0 0 15px #6e67aa;
        }

        a[href*="restock_item.php"]:hover {
            background-color: orange;
            cursor: pointer;
            box-shadow: 0 0 15px #6e67aa;
        }

        a[href*="add_item.php"]:hover {
            background-color: yellowgreen;
            cursor: pointer;
            box-shadow: 0 0 15px #6e67aa;
        }

        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            border-radius: 5px;
            color: white;
            background-color: rgba(7, 149, 66, 0.12156862745098039);
            box-shadow: 0px 0px 2px #259c08;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .alert-success {
            background-color: rgba(7, 149, 66, 0.12156862745098039);
        }

        .alert-info {
            background-color: rgba(7, 73, 149, 0.12156862745098039);
        }

        .alert-warning {
            background-color: rgba(220, 128, 1, 0.16);
        }

        .alert-danger {
            background-color: rgba(220, 17, 1, 0.16);
        }

        .table-container {
            overflow-x: auto;
            /* Allow horizontal scrolling if needed */
            margin-top: 20px;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th,
        .inventory-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .inventory-table th {
            background-color: #f2f2f2;
        }

        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
    <body>
        <div class="container">
            <!-- Navigation Section -->
            <div class="sidebar">
                <h2>SariStock</h2>
                <a href="record_sale.php">Record Sale</a>
                <a href="inventory_admin.php">View Inventory</a>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>

            <!-- Content Section -->
            <div class="content">
                <h1>Inventory Management</h1>

                <!-- Alert should appear here -->
                <?php if (isset($_SESSION['alert'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['alert']; ?>
                    </div>
                    <script>
                        setTimeout(function() {
                            document.querySelector('.alert').style.opacity = '0';
                            setTimeout(function() {
                                document.querySelector('.alert').remove();
                            }, 500); // After fade-out, remove the alert
                        }, 3000); // Fade out after 3 seconds
                    </script>
                    <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>

                <div class="table-container">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th colspan="5">
                                    <div class="table-header" align="left">
                                        <a href="add_item.php" class="btn add-item">+ Add New Item</a>
                                        <a href="restock_item.php" class="btn restock-item">↺ Restock Item</a>
                                    </div>
                                </th>
                                <div class="table-header">
                                    <th align="right">
                                        <input type="text" placeholder="🔍 Search Item" class="search-bar">
                                    </th>
                                </div>

                            </tr>
                            <tr>
                                <th>Item ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th align="center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['item_id']; ?></td>
                                    <td><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td>₱ <?php echo number_format($row['price'], 2); ?></td>
                                    <td>
                                        <?php
                                        if ($row['quantity'] > 10) {
                                            echo 'In Stock';
                                        } elseif ($row['quantity'] > 0) {
                                            echo 'Low Stock';
                                        } else {
                                            echo 'Out of Stock';
                                        }
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <a href="edit_item.php?item_id=<?php echo $row['item_id']; ?>">✏️</a>
                                        <a href="inventory.php?action=delete&item_id=<?php echo $row['item_id']; ?>">🗑️</a>
                                        <a href="add_item.php?item_id=<?php echo $row['item_id']; ?>">↺</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Back to Dashboard -->
                <div class="back">
                    <a href="admin_dashboard.php" class="btn btn-back">Back to Dashboard</a>
                </div>

            </div>
        </div>
    </body>
</html>