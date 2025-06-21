<?php include '../includes/config.php'; ?>
<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/$photo");

    $conn->query("INSERT INTO teachers (name, email, phone, photo, password) 
                  VALUES ('$name', '$email', '$phone', '$photo', '$password')");
    echo "Teacher added successfully.";
}
?>

<h2>Add Teacher</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="file" name="photo" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="add">Add Teacher</button>
</form>
