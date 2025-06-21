<?php include 'includes/config.php'; ?>
<?php
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $parent_phone = $_POST['parent_phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $photo = $_FILES['photo']['name'];
    $target = "uploads/" . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $sql = "INSERT INTO students (name, email, phone, parent_phone, address, photo, password) 
            VALUES ('$name', '$email', '$phone', '$parent_phone', '$address', '$photo', '$password')";
    if ($conn->query($sql)) {
        echo "Registration submitted. Please wait for admin approval.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Student Registration</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="text" name="parent_phone" placeholder="Parent Phone" required>
    <textarea name="address" placeholder="Address" required></textarea>
    <input type="file" name="photo" required>
    <input type="password" name="password" placeholder="Create Password" required>
    <button name="register">Register</button>
</form>
</body>
</html>
