<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['theme'] = $_POST['theme'];
}

$theme = $_SESSION['theme'] ?? 'light';
?>

<form method="POST">
    <select name="theme">
        <option value="light" <?php if ($theme == 'light') echo 'selected'; ?>>Light</option>
        <option value="dark" <?php if ($theme == 'dark') echo 'selected'; ?>>Dark</option>
    </select>
    <input type="submit" value="Change Theme">
</form>

<link rel="stylesheet" href="<?php echo $theme; ?>.css">
