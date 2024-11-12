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

// Get all inventory items
$sql = "SELECT * FROM inventory";
$result = mysqli_query($conn, $sql);

// Record a sale
$message = '';
if (isset($_POST['record_sale'])) {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity']; // Cast to integer for safety

    // Fetch the current inventory quantity and price of the item
    $item_sql = "SELECT price, quantity FROM inventory WHERE item_id = '$item_id'";
    $item_result = mysqli_query($conn, $item_sql);
    $item = mysqli_fetch_assoc($item_result);

    if ($item) {
        $price = $item['price'];
        $current_quantity = $item['quantity'];

        // Check if there is enough stock
        if ($quantity > $current_quantity) {
            $message = "Not enough stock available.";
            $alert_class = "alert-danger";
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
                    $message = "Sale recorded successfully! Inventory updated.";
                    $alert_class = "alert-success";
                } else {
                    $message = "Error updating inventory: " . mysqli_error($conn);
                    $alert_class = "alert-danger";
                }
            } else {
                $message = "Error recording sale: " . mysqli_error($conn);
                $alert_class = "alert-danger";
            }
        }
    } else {
        $message = "Item not found.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record a Sale</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Your existing styles remain unchanged */
        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .btn-back {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
            width: auto;
            min-width: 150px;
        }

        .btn-back:hover {
            background-color: #d32f2f;
        }

        .alert {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>SariStock</h2>
            <a href="record_sale.php">Record Sale</a>
            <a href="inventory_employee.php">View Inventory</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content">
            <h1>Record a Sale</h1>

            <?php if ($message): ?>
                <div class="alert <?php echo $alert_class; ?>" id="alert">
                    <div class="alert-message">
                        <span class="start-icon"><?php echo $alert_class === 'alert-success' ? '✔' : '❌'; ?></span>
                        <span><?php echo $message; ?></span>
                        <span class="fa-times" onclick="closeAlert()">×</span>
                    </div>

                    <script>
                        // Show the alert
                        document.getElementById("alert").style.display = "block";

                        // Hide alert after 5 seconds
                        setTimeout(function() {
                            document.getElementById("alert").style.opacity = "0";
                        }, 4000); // Alert disappears after 4 seconds
                    </script>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST">
                        <div class="form-group">
                            <label for="item_id">Item:</label>
                            <select name="item_id" id="item_id" required>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <option value="<?php echo $row['item_id']; ?>"><?php echo htmlspecialchars($row['item_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1" required>
                        </div>

                        <div class="form-group">
                            <input type="submit" name="record_sale" value="Record Sale">
                        </div>
                    </form>
                </div>
                <?php if ($role == 'Admin'): ?>
                    <a href="inventory_admin.php" class="btn-back">Back to Dashboard</a>
                <?php elseif ($role == 'Employee'): ?>
                    <a href="inventory_employee.php" class="btn-back">Back to Dashboard</a>
                <?php endif; ?>
                </div>
        </div>
        <script>
            function closeAlert() {
                document.getElementById("alert").style.display = "none";
            }
        </script>
</body>

</html>