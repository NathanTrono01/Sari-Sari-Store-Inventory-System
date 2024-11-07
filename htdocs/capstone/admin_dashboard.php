<?php
session_start(); // Start the session

// Check if user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php"); // Redirect to login if unauthorized
    exit();
}

// Admin dashboard content
echo "<h1>Admin Dashboard</h1>";
echo "<p>Welcome, " . $_SESSION['username'] . " (Admin)</p>";
echo "<a href='logout.php'>Logout</a>";
?>