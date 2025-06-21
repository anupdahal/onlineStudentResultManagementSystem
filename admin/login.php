<?php include '../includes/config.php'; session_start(); ?>
<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Simple static login, you can make this dynamic from DB
    if ($email == "admin@gmail.com" && $password == "admin123") {
        $_SESSION['admin'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<body>
<h2>Admin Login</h2>
<form method="post">
    <input type="email" name="email" placeholder="Admin Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>
</body>
</html>
