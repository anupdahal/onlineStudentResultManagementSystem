<?php 
include '../includes/config.php'; 
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM teachers WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
        if (password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No teacher found with this email";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Teacher Login</title></head>
<body>
<h2>Teacher Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <input type="email" name="email" placeholder="Teacher Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>
</body>
</html>
