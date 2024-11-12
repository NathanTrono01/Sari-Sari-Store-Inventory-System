<?php
session_start(); // Start the session

// Check if user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php"); // Redirect to login if unauthorized
    exit();
}

include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Raleway, sans-serif;
        }
    </style>
</head>

<body>
    <h1>Welcome, Admin</h1>
    <p>Here is the admin dashboard where you can manage inventory, sales, etc.</p>

</body>

</html>