<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Add new item to inventory
if (isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Check if the item already exists in the inventory
    $check_sql = "SELECT * FROM inventory WHERE item_name = '$item_name'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Item exists, update the quantity
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + $quantity;
        $update_sql = "UPDATE inventory SET quantity = '$new_quantity' WHERE item_name = '$item_name'";

        if (mysqli_query($conn, $update_sql)) {
            $message = "Item quantity updated successfully!";
            $alert_class = "alert-success";
        } else {
            $message = "Error updating item quantity: " . mysqli_error($conn);
            $alert_class = "alert-danger";
        }
    } else {
        // Item does not exist, add new item to the inventory
        $insert_sql = "INSERT INTO inventory (item_name, quantity, price) VALUES ('$item_name', '$quantity', '$price')";

        if (mysqli_query($conn, $insert_sql)) {
            $message = "Item added successfully!";
            $alert_class = "alert-success";
        } else {
            $message = "Error adding item: " . mysqli_error($conn);
            $alert_class = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Container for the form */
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
            justify-content: center;
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
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
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

        .container {
            display: flex;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        /* Alert styles */
        .alert {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transition: opacity 1s ease-out;
        }

        .alert-success {
            background-color: rgba(7, 149, 66, 0.8);
        }

        .alert-danger {
            background-color: rgba(220, 17, 1, 0.8);
        }

        .alert-message {
            display: flex;
            align-items: center;
        }

        .alert .start-icon {
            margin-right: 5px;
        }

        .alert .fa-times {
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>SariStock</h2>
            <a href="inventory_admin.php">View Inventory</a>
            <a href="add_item.php">Add Item</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content">
            <h1>Add New Item to Inventory</h1>

            <?php if (isset($message)): ?>
                <div class="alert <?php echo $alert_class; ?>" id="alert">
                    <div class="alert-message">
                        <span class="start-icon"><?php echo $alert_class === 'alert-success' ? '✔' : '❌'; ?></span>
                        <span><?php echo $message; ?></span>
                        <span class="fa-times" onclick="closeAlert()">×</span>
                    </div>
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
                        <label for="item_name">Item Name:</label>
                        <input type="text" name="item_name" id="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" name="price" id="price" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="add_item" value="Add Item" class="btn add-item">
                    </div>
                </form>
            </div>

            <a href="inventory_admin.php" class="btn btn-back">Back to Inventory</a>
        </div>
    </div>

    <script>
        function closeAlert() {
            document.getElementById("alert").style.display = "none";
        }
    </script>
</body>

</html>
