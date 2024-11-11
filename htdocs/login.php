<?php
session_start(); // Start the session
include ('db.php'); // Include database configuration

$error = ''; // Initialize error message

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user inputs
    if ($username && $password) {
        // Simple SQL query to retrieve user information
        $result = mysqli_query($conn, "SELECT user_id, password, role FROM users WHERE username = '$username'");

        // Check if a user was found
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Store session variables
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];

                // Redirect based on user role
                header("Location: " . ($row['role'] == 'Admin' ? "admin_dashboard.php" : "employee_dashboard.php"));
                exit();
            }
        }
        $error = "Invalid username or password.";
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($error) echo "<div style='color:red;'>$error</div>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
</body>
</html>