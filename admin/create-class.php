<?php include '../includes/config.php'; ?>
<?php
if (isset($_POST['create'])) {
    $class_name = $_POST['class_name'];
    $conn->query("INSERT INTO classes (class_name) VALUES ('$class_name')");
    echo "Class created.";
}
?>

<h2>Create Class or Faculty</h2>
<form method="post">
    <input type="text" name="class_name" placeholder="e.g. BCA 1st Sem" required>
    <button name="create">Create</button>
</form>
