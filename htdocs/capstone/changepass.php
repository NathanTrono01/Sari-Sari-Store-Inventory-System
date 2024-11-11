<?php
include('db.php');

if (isset($_POST['submit'])) {
    $userId = $_SESSION['user_id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password='$newPassword' WHERE id='$userId'";
    mysqli_query($conn, $sql);
    echo "Password updated successfully.";
}
?>
<form method="POST">
    <input type="password" name="new_password" required placeholder="New Password">
    <input type="submit" value="Change Password">
</form>