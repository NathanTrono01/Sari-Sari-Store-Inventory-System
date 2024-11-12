<!-- navbar.php -->
<?php
session_start(); // Start session to check if the user is logged in

// Check if user is logged in and fetch role
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
} else {
    $role = ''; // Set role to empty if not logged in
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation with Dropdown</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 300;
            line-height: 1.42em;
            color: #202020;
            background-color: #ffffff;
            margin: 0;
            /* Reset body margin */
        }

        body::before {
            content: '';
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, transparent 20%, #1F2739 120%);
            opacity: 0.7;
            margin: 0 auto;
        }

        nav {
            text-align: center;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #a4a4cc;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        nav a {
            background-color: #756eb2;
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        nav a:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.5s;

        }

        nav a:hover:before {
            left: 100%;
        }

        nav a:hover {
            background-color: #6c63ac;
            box-shadow: 0 0 15px #6e67aa;
            transform: translateY(-3px);
        }

        .dropdown {
            display: inline-block;
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #603F26;
            min-width: 200px;
            /* Adjusted for better layout */
            z-index: 1;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            text-align: left;
            margin: 5px 5px;
            /* Add margin for spacing */
        }

        .dropdown-content a:hover {
            background-color: #982B1C;
        }

        @media (max-width: 768px) {
            nav {
                padding: 10px 0;
            }

            nav a {
                padding: 8px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <nav>
        <?php if ($role == 'Admin'): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="inventory_admin.php">Inventory</a>
            <a href="sales.php">Sales</a>
            <a href="analytics.php">Analytics</a>
        <?php elseif ($role == 'Employee'): ?>
            <a href="employee_dashboard.php">Dashboard</a>
            <a href="inventory_employee.php">Inventory</a>
            <a href="record_sale.php">Record Sale</a>
        <?php endif; ?>

        <!-- Common Logout link for both Admin and Employee -->
        <a href="logout.php">Logout</a>
    </nav>

</body>

</html>