<?php
session_start(); // Start the session
include('db.php');

// Check if user is logged in and is an Employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Employee') {
    header("Location: login.php"); // Redirect to login if unauthorized
    exit();
}

// Employee dashboard content
echo "<h1>Employee Dashboard</h1>";
echo "<p>Welcome, " . $_SESSION['username'] . " (Employee)</p>";
echo "<a href='logout.php'>Logout</a>";
?>